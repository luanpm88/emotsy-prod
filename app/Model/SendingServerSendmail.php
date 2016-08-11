<?php

/**
 * DeliveryServerSendmail class
 *
 * Manage SMTP delivery servers
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

class SendingServerSendmail extends SendingServer
{
    protected $table = 'sending_servers';
    
    public function send($message, $params = array())
    {
        try {
            $this->addMtaHeaders($message);
            
            $transport = \Swift_SendmailTransport::newInstance($this->sendmail_path . ' -bs');

            // Create the Mailer using your created Transport
            $mailer = \Swift_Mailer::newInstance($transport);

            // Actually send
            $sent = $mailer->send($message);

            if ($sent) {
                Log::info('Sent!');
      
                return array(
                    'runtime_message_id' => $sent['MessageId'],
                    'status' => self::DELIVERY_STATUS_SENT
                );
            } else {
                Log::warning('Sending failed');
                return array(
                    'status' => self::DELIVERY_STATUS_FAILED,
                    'error' => 'Unknown SMTP error'
                );
            }
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
