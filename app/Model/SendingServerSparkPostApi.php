<?php

/**
 * SendingServerSparkPostApi class.
 *
 * Abstract class for SparkPost API sending server
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
 * @link       http://acellemail.com
 *
 */

namespace Acelle\Model;

use Acelle\Library\Log;
use SparkPost\SparkPostException;
use Acelle\Exceptions\CampaignErrorException;

class SendingServerSparkPostApi extends SendingServerSparkPost
{
    protected $table = 'sending_servers';

    /**
     * Send the provided message.
     *
     * @return array sending results
     * @param message
     */
    public function send($message, $params = array())
    {
        try {
            $this->setupWebhooks();

            $response = $this->client()->transmissions->post($this->prepareEmail($message));

            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();

                return array(
                    'runtime_message_id' => $body['results']['id'],
                    'status' => self::DELIVERY_STATUS_SENT,
                );
            } else {
                // throw the exception with body string
                throw new \Exception($response->getBody(true));
            }
        } catch (SparkPostException $e) {
            // terminate the entire campaign in case of Spark Post error
            throw new CampaignErrorException($e->getMessage());
        } catch (\Exception $e) {
            Log::warning('SparkPost failed to send');
            Log::warning($e->getMessage());

            return array(
                'status' => self::DELIVERY_STATUS_FAILED,
                'error' => $e->getMessage(),
            );
        }
    }

    /**
     * Prepare the email message to be sent by SparkPost API
     *
     * @return Array SparkPost formatted message
     * @param SwiftMessage message
     */
    private function prepareEmail($message)
    {
        $toEmail = array_keys($message->getTo())[0];
        $toName = array_values($message->getTo())[0];

        return [
            'content' => [
                'email_rfc822' => $message->toString()
            ],
            'recipients' => [
                [
                    'address' => [
                        'name' => $toName,
                        'email' => $toEmail,
                    ],
                ],
            ],

        ];
    }
}
