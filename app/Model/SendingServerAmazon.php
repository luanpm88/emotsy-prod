<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;
use Mailixa\Model\Setting;

class SendingServerAmazon extends SendingServer
{
    // @TODO change Mailixa to something else
    const SNS_TOPIC = 'MAILIXAHANDLER';

    public $notificationTypes = array('Bounce', 'Complaint');
    public static $snsClient = NULL;
    public static $sesClient = NULL;
    public static $isSnsSetup = false;

    public function snsClient()
    {
        if (!self::$snsClient) {
            self::$snsClient = \Aws\Sns\SnsClient::factory(array(
                'key'    => trim($this->aws_access_key_id),
                'secret' => trim($this->aws_secret_access_key),
                'region' => $this->aws_region
            ));
        }

        return self::$snsClient;
    }
  
    public function sesClient()
    {
        if (!self::$sesClient) {
            self::$sesClient = \Aws\Ses\SesClient::factory(array(
                'key'    => trim($this->aws_access_key_id),
                'secret' => trim($this->aws_secret_access_key),
                'region' => $this->aws_region
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
                'Identity'          => $awsIdentity,
                'ForwardingEnabled' => true
            ));
        } catch (\Exception $e) {
            $verifyByDomain = true;
            Log::warning("From Email address {$params['from_email']} not verified by Amazon SES, using domain instead");
        }
        
        if ($verifyByDomain) {
            // Use domain name as Aws Identity
            $awsIdentity = substr(strrchr($params['from_email'], "@"), 1); // extract domain from email
            $this->sesClient()->setIdentityFeedbackForwardingEnabled(array(
                'Identity'          => $awsIdentity, // extract domain from email
                'ForwardingEnabled' => true
            ));
        }

        $topicResponse = $this->snsClient()->createTopic(array('Name' => self::SNS_TOPIC));
        //$subscribeUrl  = Option::get('campaign_notification_url');
        // @TODO sửa hard-coded here
        $subscribeUrl  = Setting::get("url_delivery_handler");
        $subscribeUrl  = 'http://52.76.202.5:8888/delivery/notify';

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
                'Identity'          => $awsIdentity,
                'NotificationType'  => $type,
                'SnsTopic'          => $topicResponse->get('TopicArn'),
            ));
        }

        self::$isSnsSetup = true;
    }
}
