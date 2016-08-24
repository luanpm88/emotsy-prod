<?php

/**
 * DeliveryServerPhpMail class.
 *
 * Delivery server using PHP mail() function to send emails
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://mailixa.net/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@mailixa.net>
 * @author     L. Pham <l.pham@mailixa.net>
 * @copyright  Acelle Co., Ltd.
 * @license    Acelle Co., Ltd.
 *
 * @version    1.0
 *
 * @link       http://mailixa.net
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Log;

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

            // Actually send
            $sent = $mailer->send($message);

            if ($sent) {
                Log::info('Sent!');

                return array(
                    'runtime_message_id' => $sent['MessageId'],
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
                'message_id' => null,
            );
        }
    }
}
