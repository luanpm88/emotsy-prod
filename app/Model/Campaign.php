<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_SENDING = 'sending';
    const STATUS_PAUSED = 'paused';
    const STATUS_DONE = 'done';

    /**
     * Get the user that owns the phone.
     */
    public function mailList()
    {
        return $this->belongsTo('Mailixa\Model\MailList');
    }

    public static function getPendingCampaigns()
    {
        return self::where('status', self::STATUS_PENDING)->get();
    }

    /**
     * Mark the campaign as 'done' or 'sent'
     *
     */
    public function done()
    {
        $this->status = self::STATUS_DONE;
        $this->save();
    }

    /**
     * Mark the campaign as 'done' or 'sent'
     *
     */
    public function sending()
    {
        $this->status = self::STATUS_SENDING;
        $this->save();
    }

    /**
     * Mark the campaign as 'done' or 'sent'
     *
     */
    public function pending()
    {
        $this->status = self::STATUS_PENDING;
        $this->save();
    }

    /**
     * Send Campaign
     * Iterate through all subscribers and send email
     */
    public function start()
    {
        try {
            $this->sending();
            Log::info("Start sending campaign `{$this->name}` (ID: {$this->id})");

            $subscribers = $this->getPendingSubscribers();
            Log::info(sizeof($_subscribers) . " subscriber(s) selected");

            $_index = 0;
            foreach ($subscribers as $subscriber) {
                $_index += 1;
                Log::info("Try to send to subscriber `{$subscriber->email}` ({$_index}/" . sizeof($_subscribers) . ")");
                $message = $this->prepareEmail($subscriber);
                $server = $this->pickServer();
          
                if ($this->customer->isOverQuota()) {
                    throw new \Exception("Customer (ID: {$this->customer->customer_id}) has reached sending quota");
                }

                $sent = $server->sendEmail($message, array(
                    'delivery_for' => DeliveryServer::DELIVERY_FOR_CAMPAIGN,
                    'customer_id' => (int)$this->customer_id,
                    'customer_countable' => true,
                    'from_email' => $this->from_email
                ));

                $this->logDelivery($sent, $subscriber);
            }

            $this->done();
            Log::info("Campaign sent!");
        } catch (\Exception $e) {
            $this->pending();
            Log::error($e->getMessage());
            Log::error("Campaign `{$this->name}` (ID: {$this->campaign_id}) deferred!");
        }
        
        // reset server pools: just in case DeliveryServerAmazonSesWebApi
        // --> setup SNS requires using from_email of the corresponding campaign
        // but SNS is only made once when the server is initiated
        DeliveryServer::resetServerPools();
    }

    /**
     * Log Delivery
     * Log delivery message, used for later tracking
     */
    public function logDelivery($response, $subscriber)
    {
        $log = new CampaignDeliveryLog();
        $log->campaign_id = $this->campaign_id;
        $log->email_message_id = $response['MessageId'];
        $log->subscriber_id = $subscriber->subscriber_id;
        $log->status = CampaignDeliveryLog::STATUS_SUCCESS;
        $log->save();
    }

    public function pickServer()
    {
        return DeliveryServer::pickServer();
    }
  
    /**
     * Transform Tags
     * Transform tags to actual values before sending
     */
    public function transformTags($message, $subscriber)
    {
        $tags = array(
            'FIRST_NAME' => $subscriber->getTagValue('FNAME'),
            'LAST_NAME' => $subscriber->getTagValue('LNAME'),
            'SUBSCRIBER_EMAIL' => $subscriber->email,
            'CAMPAIGN_NAME' => $this->name,
            'CAMPAIGN_UID' => $this->campaign_uid,
            'CAMPAIGN_SUBJECT' => $this->subject,
            'CAMPAIGN_FROM_EMAIL' => $this->from_email,
            'CAMPAIGN_FROM_NAME' => $this->from_name,
            'CAMPAIGN_REPLY_TO' => $this->reply_to,
            'SUBSCRIBER_UID' => $subscriber->subscriber_uid,
            'CURRENT_YEAR' => date("Y"),
            'CURRENT_MONTH' => date("m"),
            'CURRENT_DAY' => date("d"),
            'UNSUBSCRIBE_URL' => str_replace('SUBSCRIBER_UID', $subscriber->subscriber_uid, str_replace('CAMPAIGN_UID', $this->campaign_uid, Option::get('unsubscribe_url'))),
            'COMPANY_NAME' => $this->list_company->name,
            'COMPANY_COUNTRY' => $this->list_company->country->name,
            'COMPANY_ZONE' => $this->list_company->zone->name,
            'COMPANY_CITY' => $this->list_company->city,
            'COMPANY_ADDRESS_1' => $this->list_company->address_1,
            'COMPANY_ADDRESS_2' => $this->list_company->address_2,
            'COMPANY_PHONE' => $this->list_company->phone,
            'LIST_NAME' => $this->subscriber_list->name,
            'LIST_DESCRIPTION' => $this->subscriber_list->description,
            'LIST_SUBJECT' => $this->list_default->subject,
            'LIST_FROM_NAME' => $this->list_default->from_name,
            'LIST_FROM_EMAIL' => $this->list_default->from_email
        );

        foreach ($tags as $tag => $value) {
            $message = str_replace("[$tag]", $value, $message);
        }

        return $message;
    }
  
    /**
     * Get Pending Subscribers
     * Select only subscribers that are ready for sending. Those whose status is `blacklisted`, `pending` or `unconfirmed` are not included
     */
    public function getPendingSubscribers()
    {
        // prepare statement
        $sql = "SELECT ls.* FROM &subscriber ls LEFT JOIN (SELECT * FROM &canpaign_delivery_log WHERE campaign_id = :campaign_id) cdl ON ls.subscriber_id = cdl.subscriber_id WHERE ls.list_id = :list_id AND cdl.log_id IS NULL AND ls.status = :status";

        // bind SQL parameters using BUILD_SQL utility
        $sql = MyHelper::buildSql($sql, array(
            "campaign_id" => $this->campaign_id,
            "list_id" => $this->subscriber_list->list_id,
            "status" => Subscriber::STATUS_CONFIRMED,
            "subscriber" => Subscriber::$table_name,
            "canpaign_delivery_log" => CampaignDeliveryLog::$table_name
        ));
        return Subscriber::find_by_sql($sql);
    }

    /**
     * Append Open Tracking URL
     * Append open-tracking URL to every email message
     */
    public function appendOpenTrackingUrl($body, $subscriber)
    {
        $tracking_url = str_replace('CAMPAIGN_UID', $this->campaign_uid, Option::get('open_track_url'));
        $tracking_url = str_replace('SUBSCRIBER_UID', $subscriber->subscriber_uid, $tracking_url);
        return $body . '<img src="' . $tracking_url . '" width="0" height="0" />';
    }
    
    /**
     * Prepare Email
     * Prepare the email content using Swift Mailer
     * @input Raw email content
     * @return MIME text message
     */
    public function prepareEmail($subscriber)
    {
        // build the message
        $message = \Swift_Message::newInstance();

        $message->setContentType("text/plain; charset=utf-8");
      
        $message->getHeaders()->addTextHeader('X-Mailspeed-Campaign-Id', $this->campaign_uid);
        $message->getHeaders()->addTextHeader('X-Mailspeed-Subscriber-Id', $subscriber->subscriber_uid);
        $message->getHeaders()->addTextHeader('X-Mailspeed-Customer-Id', $this->customer->customer_uid);

        $message->setSubject($this->subject);
        $message->setFrom(array($this->from_email => $this->from_name));
        $message->setTo($subscriber->email);
        $message->setReplyTo($this->reply_to);
        //$message->setEncoder(Swift_Encoding::get8bitEncoding());

        $body = $this->transformTags($this->campaign_template->content, $subscriber);
        $body = $this->appendOpenTrackingUrl($body, $subscriber);

        $message->setBody($body, 'text/html');
        $message->addPart($this->campaign_template->plain_text, 'text/plain');

        // attachment
        //$message->attach(Swift_Attachment::fromPath('/tmp/gaugau.csv'));

        return $message;
    }
}
