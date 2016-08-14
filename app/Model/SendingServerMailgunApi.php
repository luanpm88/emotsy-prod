<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;
use Mailixa\Library\StringHelper;
use Mailgun\Mailgun;

class SendingServerMailgunApi extends SendingServer
{
    protected $table = 'sending_servers';
    private static $client = NULL;
    private static $isWebhookSetup = false;
    
    public function send($message, $params = array())
    {
            $domain = StringHelper::getDomainFromEmail($params["from_email"]);
            //$domain = 'sandboxe80f3790d6784dcea27cbf61831dc094.mailgun.org';
            $this->setupWebhooks($domain);
            $result = $this->client()->sendMessage(
                $domain, array(
                    'from' => $params["from_email"],
                    'to' => $params["to"]
                    //'to' => 'xfake03940384304893@gmail.com'
                ),
                $message->toString()
            );

            Log::info('Sent!');
      
            return array(
                'runtime_message_id' => StringHelper::cleanupMessageId($result->http_response_body->id),
                'status' => self::DELIVERY_STATUS_SENT
            );
        
        
    }

    public function client()
    {
        if (!self::$client) {
            self::$client = new Mailgun($this->api_key);
        }

        return self::$client;
    }

    private function setupWebhooks($domain) {
        if (self::$isWebhookSetup) {
            return true;
        }

        Log::info('setting up webhooks for bounce/complaints');
        
        // @TODO 
        $subscribeUrl  = StringHelper::joinUrl( Setting::get("url_delivery_handler"), $this->uid);
        $subscribeUrl  = StringHelper::joinUrl( 'http://52.76.202.5:8888/delivery/notify', $this->uid);
        
        try {
            $result = $this->client()->delete("domains/$domain/webhooks/bounce");
        } catch (\Exception $e) {
            // just ignore
        }

        try {
            $result = $this->client()->delete("domains/$domain/webhooks/spam");
        } catch (\Exception $e) {
            // just ignore
        }
        
        $result = $this->client()->post("domains/$domain/webhooks", array(
            'id'  => 'bounce',
            'url' => $subscribeUrl
        ));
        Log::info('bounce webhook created');

        $result = $this->client()->post("domains/$domain/webhooks", array(
            'id'  => 'spam',
            'url' => $subscribeUrl
        ));
        Log::info('complaint webhook created');

        self::$isWebhookSetup = true;
    }
}
