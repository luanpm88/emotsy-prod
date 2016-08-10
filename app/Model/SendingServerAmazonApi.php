<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;

class SendingServerAmazonApi extends SendingServerAmazon
{
    protected $table = 'sending_servers';
    
    public function send($message, $params = array())
    {
        try {
            $this->addMtaHeaders($message);
            
            $this->setupSns($params);

            $sent = $this->sesClient()->sendRawEmail(array(
              'RawMessage' => array(
                'Data' => base64_encode($message->toString())
              )
            ));

            Log::info('Sent!');
      
            return array(
                'runtime_message_id' => $sent['MessageId'],
                'status' => self::DELIVERY_STATUS_SENT
            );
        
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            return array(
                'status' => self::DELIVERY_STATUS_FAILED,
                'error' => $e->getMessage()
            );
        }
    }
}
