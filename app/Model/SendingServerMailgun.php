<?php

namespace Acelle\Model;

use Acelle\Library\Log;
use Acelle\Library\StringHelper;
use Mailgun\Mailgun;

class SendingServerMailgun extends SendingServer
{
    protected $table = 'sending_servers';
    public static $client = null;
    public static $isWebhookSetup = false;

    public function send($message, $params = array())
    {
        // for overwriting
    }

    public function client()
    {
        if (!self::$client) {
            self::$client = new Mailgun($this->api_key);
        }

        return self::$client;
    }

    public function setupWebhooks($domain)
    {
        if (self::$isWebhookSetup) {
            return true;
        }

        Log::info('setting up webhooks for bounce/complaints');

        // @TODO 
        $subscribeUrl = StringHelper::joinUrl(Setting::get('url_delivery_handler'), $this->uid);
        $subscribeUrl = StringHelper::joinUrl('http://52.76.202.5:8888/delivery/notify', $this->uid);

        try {
            $result = $this->client()->delete("domains/$domain/webhooks/bounce");
        } catch (\Exception $e) {
            // just ignore
        }

        try {
            $result = $this->client()->delete("domains/$domain/webhooks/spam");
        } catch (\Exception $e) {
            // just ignore
        }

        $result = $this->client()->post("domains/$domain/webhooks", array(
            'id' => 'bounce',
            'url' => $subscribeUrl,
        ));
        Log::info('bounce webhook created');

        $result = $this->client()->post("domains/$domain/webhooks", array(
            'id' => 'spam',
            'url' => $subscribeUrl,
        ));
        Log::info('complaint webhook created');

        self::$isWebhookSetup = true;
    }
}
