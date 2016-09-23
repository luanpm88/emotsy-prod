<?php

/**
 * SendingServerMailgunApi class.
 *
 * Abstract class for Mailgun API sending server
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

use Acelle\Library\Log;
use Acelle\Library\StringHelper;
use SendGrid\Mail;
use SendGrid\Email;
use SendGrid\Content;

class SendingServerSendGridApi extends SendingServerSendGrid
{
    protected $table = 'sending_servers';
    
    /**
     * Send the provided message.
     *
     * @return boolean
     * @param message
     */
    // Inherit class to implementation of this method
    public function send($message, $params = array())
    {
        try {
            $this->setupWebhooks();
            $mail = $this->prepareEmail($params['campaign'], $params['subscriber']);
            
            $response = $this->client()->client->mail()->send()->post($mail);
            $statusCode = $response->statusCode();
            
            # if response from SendGrid is 200, 202, 2xx
            if (preg_match("/^2../i", $statusCode)) {
                Log::info('Sent!');

                return array(
                    'runtime_message_id' => StringHelper::cleanupMessageId($this->getMessageId($response->headers())),
                    'status' => self::DELIVERY_STATUS_SENT,
                );
            } else {
                throw new \Exception("{$statusCode} ".$response->body());
            }
        } catch (\Exception $e) {
            Log::warning('Sending failed');
            Log::warning($e->getMessage());

            return array(
                'status' => self::DELIVERY_STATUS_FAILED,
                'error' => $e->getMessage(),
            );
        }
    }

    private function prepareEmail($campaign, $subscriber) {
        $customHeaders = $campaign->getCustomHeaders($subscriber, $this);
        // @TODO use a configurable name for Acelle Mail instead
        $msgId = $customHeaders['X-Acelle-Message-Id'];

        $from = new Email($campaign->from_name, $campaign->from_email);
        $to = new Email(null, $subscriber->email);
        $plain = new Content("text/plain", $campaign->getPlainContent($subscriber, $msgId));
        $html = new Content("text/html", $campaign->getHtmlContent($subscriber, $msgId));
        
        $mail = new Mail($from, $campaign->subject, $to, $plain);
        foreach($customHeaders as $key => $value) {
            $mail->addHeader($key, $value);
        }
        $mail->addContent($html);
        return $mail;
    }
}
