<?php

namespace Acelle\Library\Webhook;

class AutomationWebhook
{
    public const EVENT = 'terminate_subscription';

    public static function run($automationId)
    {
        // Get all webhooks
        $webhooks = \Acelle\Model\Webhook::active()->where('event', static::EVENT)->get();

        // Run all
        foreach ($webhooks as $webhook) {
            $webhook->run([
                'automation_id' => $automationId,
            ]);
        }
    }
}
