<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;

class SendingServerAmazonApi extends SendingServerAmazon
{
    protected $table = 'sending_servers';

    public $notificationTypes = array('Bounce', 'Complaint');
    public $_snsInitiated = false;

    public function send($message, $params = array())
    {
        try {
            if (!$this->_snsInitiated) {
                $this->setupSns($params);
            }

            $sent = $this->getSesClient()->sendRawEmail(array(
              'RawMessage' => array(
                'Data' => base64_encode($message->toString())
              )
            ));

            Log::info('Sent!');
      
            return array(
                'message_id' => $sent['MessageId'],
                'status' => self::DELIVERY_STATUS_SENT,
                'error' => NULL
            );
        
        } catch (\Exception $e) {
            Log::warning('Sending failed');
            return array(
                'status' => self::DELIVERY_STATUS_FAILED,
                'error' => $e->getMessage(),
                'message_id' => NULL
            );
        }
    }

    public function getSnsClient()
    {
        static $snsclients = array();
        $id = (int)$this->server_id;
        if (!empty($snsclients[$id])) {
            return $snsclients[$id];
        }
        return $snsclients[$id] = \Aws\Sns\SnsClient::factory(array(
            'key'    => trim($this->username),
            'secret' => trim($this->password),
            'region' => $this->aws_region
        ));
    }
  
    public function getSesClient()
    {
        static $clients = array();
        $id = (int)$this->server_id;
        if (!empty($clients[$id])) {
            return $clients[$id];
        }

        return $clients[$id] = \Aws\Ses\SesClient::factory(array(
            'key'    => trim($this->username),
            'secret' => trim($this->password),
            'region' => $this->aws_region
        ));
    }

    public function setupSns($params)
    {
        $awsIdentity = $params['from_email'];

        try {
            Log::info('Set up Amazon SNS for email delivery tracking');
            $this->getSesClient()->setIdentityFeedbackForwardingEnabled(array(
                'Identity'          => $awsIdentity,
                'ForwardingEnabled' => true
            ));
        } catch (\Exception $e) {
            Log::warning("From Email address {$params['from_email']} not verified by Amazon SES, using domain instead");
        }
        
        // Use domain name as Aws Identity
        $awsIdentity = substr(strrchr($params['from_email'], "@"), 1); // extract domain from email
        $this->getSesClient()->setIdentityFeedbackForwardingEnabled(array(
            'Identity'          => $awsIdentity, // extract domain from email
            'ForwardingEnabled' => true
        ));

        $topicResponse = $this->getSnsClient()->createTopic(array('Name' => 'EMSHANDLER'));
        //$subscribeUrl  = Option::get('campaign_notification_url');
        // @TODO sửa hard-coded here
        $subscribeUrl  = 'http://www.gmail.com';

        $subscribeResponse = $this->getSnsClient()->subscribe(array(
            'TopicArn' => $topicResponse->get('TopicArn'),
            'Protocol' => stripos($subscribeUrl, 'https') === 0 ? 'https' : 'http',
            'Endpoint' => $subscribeUrl,
        ));

        if (stripos($subscribeResponse->get('SubscriptionArn'), 'pending') === false) {
            $this->subscription_arn = $result->get('SubscriptionArn');
        }

        foreach ($this->notificationTypes as $type) {
            $this->getSesClient()->setIdentityNotificationTopic(array(
                'Identity'          => $awsIdentity,
                'NotificationType'  => $type,
                'SnsTopic'          => $topicResponse->get('TopicArn'),
            ));
        }

        $this->_snsInitiated = true;
    }
}
