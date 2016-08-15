<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;
use Mailixa\Library\StringHelper;
use Mailgun\Mailgun;

class SendingServerMailgunApi extends SendingServerMailgun
{
    protected $table = 'sending_servers';
    
    public function send($message, $params = array())
    {
        try {
            $this->setupWebhooks($this->domain);
            $result = $this->client()->sendMessage(
                $this->domain, array(
                    'from' => $params["from_email"],
                    'to' => $params["to"]
                    //'to' => 'em3ng0384304893@gmail.com'
                ),
                $message->toString()
            );

            Log::info('Sent!');
      
            return array(
                'runtime_message_id' => StringHelper::cleanupMessageId($result->http_response_body->id),
                'status' => self::DELIVERY_STATUS_SENT
            );
        } catch (\Exception $e) {
            Log::warning('Sending failed');
            Log::warning($e->getMessage());
            return array(
                'status' => self::DELIVERY_STATUS_FAILED,
                'error' => $e->getMessage()
            );
        }
    }
}
