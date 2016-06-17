<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class SendingServer extends Model
{
    const DKIM_SELECTOR = 'mailer';

    public static $serverMapping = array(
        'aws-ses-api'        => 'SendingServerAwsSesApi',
        'aws-ses-smtp'       => 'SendingServerAwsSesSmtp',
        'smtp'               => 'SendingServerSmtp',
        'sendmail'           => 'SendingServerSendmail',
        'php-mail'           => 'SendingServerPhpMail'
    );

    public static $serverPools = array();

    public function logMessage($message, $params)
    {
        $log = new TrackingLog();
        $log->sending_server_id = (int)$this->server_id;
        $log->user_id = $params['user_id'];
        $log->message_id = $params['message_id'];
        $log->status = TrackingLog::STATUS_SENT;
        $log->save();
    }

    public function resetServerPools() {
        self::$serverPools = array();
    }

    private function findSendingDomain($email) {
        $domain = substr(strrchr($email, "@"), 1);
        return SendingDomain::where('name', $domain)->first();
    }

    public function sign($message, $params) {
        $sendingDomain = $this->findSendingDomain($params['from_email']);
        
        if (empty($sendingDomain)) {
          return $message;
        }

        $privateKey = $sendingDomain->dkim_private_key;
        $domainName = $sendingDomain->name;
        $selector = self::DKIM_SELECTOR;
        $signer = new \Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
        $signer->ignoreHeader('Return-Path');
        $message->attachSigner($signer);
        return $message;
    }

    public function addCustomHeaders($message)
    {
        $additional_headers = unserialize($this->meta_data)["additional_headers"];
        if (!empty($additional_headers)) {
            foreach ($additional_headers as $header => $value) {
                $message->getHeaders()->addTextHeader($header, $value);
            }
        }

        $message->getHeaders()->addTextHeader('X-Ems-Delivery-Server-Uid', $this->server_id); // @TODO UID, not ID

        return $message;
    }
  
    public static function pickServer()
    {
        $sql = 'SELECT * FROM &delivery_server t WHERE `t`.`hourly_quota` = 0 OR `t`.`hourly_quota` > ( SELECT COUNT(*) FROM &delivery_server_usage_log WHERE server_id = `t`.`server_id` AND `created_at` BETWEEN DATE_FORMAT(NOW(), "%Y-%m-%d %H:00:00") AND DATE_FORMAT(NOW() + INTERVAL 1 HOUR, "%Y-%m-%d %H:00:00") )';

        $sql = MyHelper::buildSql($sql, array(
            "delivery_server" => self::$table_name,
            "delivery_server_usage_log" => DeliveryServerUsageLog::$table_name
        ));

        $servers = self::find_by_sql($sql);
    
        if (empty($servers)) {
            throw new \Exception('No delivery servers available');
        }

        $serverSelection = array();
        foreach ($servers as $server) {
            $serverSelection[$server->server_id] = (float)$server->probability;
        }

        $id = (int)RouletteWheel::generate($serverSelection);
        $selectedServer = DeliveryServer::find($id);
    
        if (!empty(self::$serverPools[$id])) {
            MyLogger::$logger->info('Reuse delivery server `' . self::$serverPools[$id]->name . "` (ID: " . $id . ")");
            return self::$serverPools[$id];
        } else {
            MyLogger::$logger->info('Initialize delivery server `' . $selectedServer->name . "` (ID: " . $id . ")");
            return self::$serverPools[$id] = self::mapServerType($selectedServer);
        }
    }

    public static function mapServerType($server)
    {
        $class_name = '\Mailixa\Sender\\' . self::$serverMapping[$server->type];
        return $class_name::find($server->server_id);
    }
}

