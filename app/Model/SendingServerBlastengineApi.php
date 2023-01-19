<?php

/**
 * SendingServerSendGridApi class.
 *
 * Abstract class for SendGrid API sending server
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

use Acelle\Library\StringHelper;
use SendGrid\Mail;

class SendingServerBlastengineApi extends SendingServerBlastengine
{
    protected $table = 'sending_servers';

    /**
     * Send the provided message.
     *
     * @return bool
     *
     * @param message
     */
    // Inherit class to implementation of this method
    public function send($message, $params = array())
    {
        $fromEmail = array_keys($message->getFrom())[0];
        $fromName = (is_null($message->getFrom())) ? null : array_values($message->getFrom())[0];
        $toEmail = array_keys($message->getTo())[0];
        $toName = (is_null($message->getTo())) ? null : array_values($message->getTo())[0];
        $replyToEmail = (is_null($message->getReplyTo())) ? $fromEmail : array_keys($message->getReplyTo())[0];

        $html = null;
        $plain = null;
        foreach ($message->getChildren() as $part) {
            $contentType = $part->getContentType();

            if ($contentType == 'text/html') {
                $html = $part->getBody();
            } elseif ($contentType == 'text/plain') {
                $plain = $part->getBody();
            }
        }

        $userId = "trialrencontru";
        $apiKey = $this->api_key;

        // トークン生成
        $str = "{$userId}{$apiKey}";
        $token = base64_encode(strtolower(hash('sha256', $str)));
        $url = 'https://app.engn.jp/api/v1/deliveries/transaction';
        // POSTデータ
        $data = [
            "from" => [
                    "email" => $fromEmail,
                    "name" => $fromName
            ],
            "to" => $toEmail,
            "subject" => $message->getSubject(),
            "encode" => "ISO-2022-JP",
            "text_part" => $plain,
            "html_part" => $html
        ];
        $data = json_encode($data);

        $header = [
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ];

        $context = [
            "http" => [
                "method"  => "POST",
                "header"  => implode("\r\n", $header),
                "content" => $data
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        $res = file_get_contents($url, false, stream_context_create($context));
        $resjson = json_decode($res, true);

        $deliveryOutput = [
            'runtime_message_id' => $resjson['delivery_id'],
            'status' => self::DELIVERY_STATUS_SENT,
        ];

        return $deliveryOutput;
    }
}
