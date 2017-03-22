<?php

/**
 * SendingServerSparkPost class.
 *
 * Abstract class for SparkPost sending server
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
 * @description: This is the Skeleton for developing a sending server which is used to send email through a 3rd service
 * the expected usage is:
 *
 *     $server = new SendingServerSparkPostApi()
 *     $server->sendMessage( $message );
 */

namespace Acelle\Model;

use Acelle\Library\Log as MailLog;
use Acelle\Library\StringHelper;
use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

class SendingServerSparkPost extends SendingServer
{
    const WEBHOOK = 'sparkpost';

    protected $table = 'sending_servers';
    public static $client = null;
    public static $isWebhookSetup = false;

    // Inherit class to implementation of this method
    public function send($message, $params = array())
    {
        // for overwriting
    }

    /**
     * Get authenticated to Mailgun and return the session object.
     *
     * @return mixed
     */
    public function client()
    {
        if (!self::$client) {
            $httpClient = new GuzzleAdapter(new Client());
            $sparky = new SparkPost($httpClient, ['key' => $this->api_key]);
            $sparky->setOptions(['async' => false]);
            self::$client = $sparky;
        }

        return self::$client;
    }

    /**
     * Setup webhooks for processing bounce and feedback loop.
     *
     * @return mixed
     */
    public function setupWebhooks()
    {
        if (self::$isWebhookSetup) {
            return true;
        }

        // @TODO 03 get the URL listening for bounce/feedback notification
        // @responsible: Acelle Mail team
        $webhookUrl = StringHelper::joinUrl(Setting::get('url_delivery_handler'), self::WEBHOOK);

        try {
            $promise = $this->client()->request('POST', 'webhooks', [
                "name" => "Acelle Mail Webhook",
                "target" => $webhookUrl,
                "auth_type" => "none",
                "events" => [
                    "bounce",
                    "spam_complaint",
                    "out_of_band",
                    "policy_rejection"
                ]
            ]);

            self::$isWebhookSetup = true;
        } catch (\Exception $e) {
            self::$isWebhookSetup = true;
            MailLog::warning('Cannot setup Spark Post webhook, ignored!');
        }
    }
}
