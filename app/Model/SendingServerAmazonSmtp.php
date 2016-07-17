<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;

class SendingServerAmazonSmtp extends SendingServerAmazon
{
    protected $table = 'sending_servers';
    
    public function send($message, $params = array())
    {
        try {
            $this->addMtaHeaders($message);
            
            $this->setupSns($params);

            $transport = \Swift_SmtpTransport::newInstance($this->host, (int)$this->smtp_port, $this->smtp_protocol)
              ->setUsername($this->smtp_username)
              ->setPassword($this->smtp_password)
            ;

            // Create the Mailer using your created Transport
            $mailer = \Swift_Mailer::newInstance($transport);
            
            // Sign with DKIM
            //$this->sign($message, $params);

            // Actually send
            $sent = $mailer->send($message);

            if ($sent) {
                Log::info('Sent!');
      
                return array(
                    'message_id' => $sent['MessageId'],
                    'status' => self::DELIVERY_STATUS_SENT,
                    'error' => NULL
                );
            } else {
                Log::warning('Sending failed');
                return array(
                    'status' => self::DELIVERY_STATUS_FAILED,
                    'error' => 'Unknown SMTP error',
                    'message_id' => NULL
                );
            }
        } catch (\Exception $e) {
            Log::warning('Sending failed');
            return array(
                'status' => self::DELIVERY_STATUS_FAILED,
                'error' => $e->getMessage(),
                'message_id' => NULL
            );
        }
    }
}
