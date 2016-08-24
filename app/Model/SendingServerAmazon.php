<?php

namespace Acelle\Model;

use Acelle\Library\Log;
use Acelle\Library\StringHelper;

class SendingServerAmazon extends SendingServer
{
    const SNS_TOPIC = 'ACELLEHANDLER';

    public $notificationTypes = array('Bounce', 'Complaint');
    public static $snsClient = null;
    public static $sesClient = null;
    public static $isSnsSetup = false;

    public function snsClient()
    {
        if (!self::$snsClient) {
            self::$snsClient = \Aws\Sns\SnsClient::factory(array(
                'key' => trim($this->aws_access_key_id),
                'secret' => trim($this->aws_secret_access_key),
                'region' => $this->aws_region,
            ));
        }

        return self::$snsClient;
    }

    public function sesClient()
    {
        if (!self::$sesClient) {
            self::$sesClient = \Aws\Ses\SesClient::factory(array(
                'key' => trim($this->aws_access_key_id),
                'secret' => trim($this->aws_secret_access_key),
                'region' => $this->aws_region,
            ));
        }

        return self::$sesClient;
    }

    public function setupSns($params)
    {
        if (self::$isSnsSetup) {
            return true;
        }

        Log::info('Set up Amazon SNS for email delivery tracking');

        $awsIdentity = $params['from_email'];
        $verifyByDomain = false;
        try {
            $this->sesClient()->setIdentityFeedbackForwardingEnabled(array(
                'Identity' => $awsIdentity,
                'ForwardingEnabled' => true,
            ));
        } catch (\Exception $e) {
            $verifyByDomain = true;
            Log::warning("From Email address {$params['from_email']} not verified by Amazon SES, using domain instead");
        }

        if ($verifyByDomain) {
            // Use domain name as Aws Identity
            $awsIdentity = substr(strrchr($params['from_email'], '@'), 1); // extract domain from email
            $this->sesClient()->setIdentityFeedbackForwardingEnabled(array(
                'Identity' => $awsIdentity, // extract domain from email
                'ForwardingEnabled' => true,
            ));
        }

        $topicResponse = $this->snsClient()->createTopic(array('Name' => self::SNS_TOPIC));
        $subscribeUrl = StringHelper::joinUrl(Setting::get('url_delivery_handler'), $this->uid);

        $subscribeResponse = $this->snsClient()->subscribe(array(
            'TopicArn' => $topicResponse->get('TopicArn'),
            'Protocol' => stripos($subscribeUrl, 'https') === 0 ? 'https' : 'http',
            'Endpoint' => $subscribeUrl,
        ));

        if (stripos($subscribeResponse->get('SubscriptionArn'), 'pending') === false) {
            $this->subscription_arn = $result->get('SubscriptionArn');
        }

        foreach ($this->notificationTypes as $type) {
            $this->sesClient()->setIdentityNotificationTopic(array(
                'Identity' => $awsIdentity,
                'NotificationType' => $type,
                'SnsTopic' => $topicResponse->get('TopicArn'),
            ));
        }

        self::$isSnsSetup = true;
    }
}
