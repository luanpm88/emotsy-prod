<?php

namespace Mailixa\Model;

use Mailixa\Library\Log;
use Mailixa\Library\AmazonSmtpTransport;

class SendingServerAmazonSmtp extends SendingServerAmazon
{
    protected $table = 'sending_servers';

    public function send($message, $params = array())
    {
        try {
            $this->addMtaHeaders($message);

            $this->setupSns($params);

            $transport = AmazonSmtpTransport::newInstance($this->host, (int) $this->smtp_port, $this->smtp_protocol)
              ->setUsername($this->smtp_username)
              ->setPassword($this->smtp_password)
            ;

            // Create the Mailer using your created Transport
            $mailer = \Swift_Mailer::newInstance($transport);

            // Actually send
            $sent = $mailer->send($message);

            if ($sent) {
                Log::info('Sent!');

                return array(
                    'runtime_message_id' => $transport->getMessageId(),
                    'status' => self::DELIVERY_STATUS_SENT,
                );
            } else {
                Log::warning('Sending failed');

                return array(
                    'status' => self::DELIVERY_STATUS_FAILED,
                    'error' => 'Unknown SMTP error',
                );
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
}
