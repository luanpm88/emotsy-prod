<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;
use Mailixa\Library\StringHelper;
use Mailgun\Mailgun;

class SendingServerMailgunApi extends SendingServer
{
    protected $table = 'sending_servers';
    
    public function send($message, $params = array())
    {
        try {
            $client = new Mailgun($this->api_key);
            $domain = StringHelper::getDomainFromEmail($params["from_email"]);
            //$domain = 'sandboxe80f3790d6784dcea27cbf61831dc094.mailgun.org';

            $result = $client->sendMessage(
                $domain, array(
                    'from' => $params["from_email"],
                    'to' => $params["to"]
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
