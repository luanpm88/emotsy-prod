<?php

/**
 * DeliveryServerPhpMail class
 *
 * Delivery server using PHP mail() function to send emails
 *
 * LICENSE: This product includes software developed at
 * the Mailixa Co., Ltd. (http://mailixa.net/).
 *
 * @category   MVC Model
 * @package    Mailer
 * @author     N. Pham <n.pham@mailixa.net>
 * @author     L. Pham <l.pham@mailixa.net>
 * @copyright  Mailixa Co., Ltd.
 * @license    Mailixa Co., Ltd.
 * @version    1.0
 * @link       http://mailixa.net
 */

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;

class SendingServerPhpMail extends SendingServer
{
    protected $table = 'sending_servers';
    
    public function send($message, $params = array())
    {
        try {
            $this->addMtaHeaders($message);
            
            $transport = \Swift_MailTransport::newInstance();

            // Create the Mailer using your created Transport
            $mailer = \Swift_Mailer::newInstance($transport);
            
            // Sign with DKIM
            $this->sign($message, $params);

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
