<?php

/**
 * SendingServerElasticEmail class.
 *
 * Abstract class for Mailjet sending server
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Acelle\Library\Log as MailLog;
use Acelle\Library\StringHelper;

class SendingServerElasticEmail extends SendingServer
{
    const WEBHOOK = 'elasticemail';
    const API_ENDPOINT = 'https://api.elasticemail.com/v2';

    protected $table = 'sending_servers';
    public static $isWebhookSetup = false;
    
    /**
     * Setup webhooks for processing bounce and feedback loop.
     *
     * @return mixed
     */
    public function setupWebhooks()
    {
        if (self::$isWebhookSetup) {
            return true;
        }

        try {
            $subscribeUrl = StringHelper::joinUrl(Setting::get('url_delivery_handler'), self::WEBHOOK);

            $response = file_get_contents(self::API_ENDPOINT."/account/updatehttpnotification?apikey=".$this->api_key."&url=".$subscribeUrl."&settings={sent:true,opened:true,clicked:true,unsubscribed:true,complaints:true,error:true}");
            if ($response == '{"success":true}') {
                MailLog::info("webhook set!");
                self::$isWebhookSetup = true;
            } else {
                throw new Exception("Cannot setup webhook. Response from server: ".$response);
            }
        } catch (\Exception $e) {
            MailLog::warning($e->getMessage());
        }
    }
    
    /**
     * Implematation of Elastic mail/send API
     *
     * @param mixed $campaign
     * @param mixed $subscriber
     * @param string $msgId
     *
     */
    function sendElasticEmail($campaign, $subscriber, $msgId)
    {
        $res = "";
        $body_html = $campaign->getHtmlContent($subscriber, $msgId);
        $body_text = $campaign->getPlainContent($subscriber, $msgId);

        $customHeaders = $campaign->getCustomHeaders($subscriber, $this);
        // @TODO use a configurable name for Acelle Mail instead
        // retain the existing message-id, overwrite the newly generated one
        $customHeaders['X-Acelle-Message-Id'] = $msgId;

        $data = "api_key=".urlencode($this->api_key);
        $data .= "&from=".urlencode($campaign->from_email);
        $data .= "&from_name=".urlencode($campaign->from_name);
        $data .= "&to=".urlencode($subscriber->email);
        $data .= "&subject=".urlencode($campaign->getSubject($subscriber, $msgId));
        
        if($body_html) {
            $data .= "&body_html=".urlencode($body_html);
        }
        
        if($body_text) {
            $data .= "&body_text=".urlencode($body_text);
        }

        foreach(array_keys($customHeaders) as $i => $key) {
            $data .= "&header" . ($i+1) . "=" . urlencode($key) . ": " . urlencode($customHeaders[$key]);
        }

        $header = "POST /mailer/send HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($data) . "\r\n\r\n";
        $fp = fsockopen('ssl://api.elasticemail.com', 443, $errno, $errstr, 30);

        if(!$fp)
          throw new \Exception("Could not open connection with ssl://api.elasticemail.com");
        else {
          fputs ($fp, $header.$data);
          while (!feof($fp)) {
            $res .= fread ($fp, 1024);
          }
          fclose($fp);
        }

        // extract the transaction ID from socket responses
        // which is something like: f74b9f96-f89a-4cfe-813f-5f86df1cb37f
        preg_match('/[a-z0-9]{8}\-[a-z0-9]{4}\-[a-z0-9]{4}\-[a-z0-9]{4}\-[a-z0-9]{12}$/', $res, $matches);

        if (array_key_exists(0, $matches)) {
          return $matches[0];
        } else {
          throw new \Exception("Sending failed: \n".$res);
        }
    }
}
