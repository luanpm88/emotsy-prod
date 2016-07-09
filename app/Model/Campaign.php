<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;

class Campaign extends Model
{
    const STATUS_NEW     = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_SENDING = 'sending';
    const STATUS_PAUSED  = 'paused';
    const STATUS_DONE    = 'done';
    const TYPE_REGULAR    = 'regular';
    
    public static $template_tags = array(
                            array("name" => "{UNSUBSCRIBE_URL}", "required" => true),
                        );

    /**
     * Get the user that owns the phone.
     */
    public function mailList()
    {
        return $this->belongsTo('Mailixa\Model\MailList');
    }
    
    /**
     * Get the segment of the campaign.
     */
    public function segment()
    {
        return $this->belongsTo('Mailixa\Model\Segment');
    }
    
    /**
     * Get the links for campaign.
     */
    public function links()
    {
        return $this->belongsToMany('Mailixa\Model\Link', 'campaign_links');
    }

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo('Mailixa\Model\User');
    }
    
    public function trackingLogs()
    {
        return $this->hasMany('Mailixa\Model\TrackingLog');
    }
    
    public function bounceLogs()
    {
        return $this->hasManyThrough('Mailixa\Model\BounceLog', 'Mailixa\Model\TrackingLog', 'campaign_id', 'message_id', 'id');
    }
    
    public function openLogs()
    {
        return $this->hasManyThrough('Mailixa\Model\OpenLog', 'Mailixa\Model\TrackingLog', 'campaign_id', 'message_id', 'id');
    }
    
    public function clickLogs()
    {
        return $this->hasManyThrough('Mailixa\Model\ClickLog', 'Mailixa\Model\TrackingLog', 'campaign_id', 'message_id', 'id');
    }
    
    public function feedbackLogs()
    {
        return $this->hasManyThrough('Mailixa\Model\FeedbackLog', 'Mailixa\Model\TrackingLog', 'campaign_id', 'message_id', 'id');
    }
    
    /**
     * get pending campaigns
     *
     */
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
     * Mark the campaign as 'sending'
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
            Log::info("Starting campaign `{$this->name}` (ID: {$this->id})");

            $subscribers = $this->getPendingSubscribers();
            Log::info(sizeof($subscribers) . " subscriber(s) selected");

            $i = 0;
            foreach ($subscribers as $subscriber) {
                $i += 1;
                Log::info("Sending to subscriber `{$subscriber->email}` ({$i}/" . sizeof($subscribers) . ")");

                $message = $this->prepareEmail($subscriber);
                
                $server = $this->pickServer($this);
                
                /*
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
                */
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
        //DeliveryServer::resetServerPools();
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

    public function pickServer($campaign)
    {
        return SendingServer::pickServer($campaign);
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
            $message = str_replace("{".$tag."}", $value, $message);
        }

        return $message;
    }
  
    /**
     * Get Pending Subscribers
     * Select only subscribers that are ready for sending. Those whose status is `blacklisted`, `pending` or `unconfirmed` are not included
     */
    public function getPendingSubscribers()
    {
        return \DB::table('subscribers')
            ->leftJoin(\DB::raw('(SELECT * FROM tracking_logs WHERE campaign_id = :campaign_id) log', ['campaign_id' => $this->id]), 'subscribers.id', '=', 'log.subscriber_id')
            ->where('log.id', NULL)
            ->select('subscribers.*')
            ->get();
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
      
        $message->getHeaders()->addTextHeader('X-Mailspeed-Campaign-Id', $this->uid);
        $message->getHeaders()->addTextHeader('X-Mailspeed-Subscriber-Id', $subscriber->uid);
        $message->getHeaders()->addTextHeader('X-Mailspeed-User-Id', $this->user->uid);

        $message->setSubject($this->subject);
        var_dump(array($this->from_email => $this->from_name));
        $message->setFrom(array($this->from_email => $this->from_name));
        $message->setTo($subscriber->email);
        $message->setReplyTo($this->reply_to);
        //$message->setEncoder(Swift_Encoding::get8bitEncoding());

        //$body = $this->transformTags($this->html, $subscriber);
        //$body = $this->appendOpenTrackingUrl($body, $subscriber);
        $message->setBody($this->html, 'text/html');

        //$plain = $this->transformTags($this->plain);
        $message->addPart($this->plain, 'text/plain');

        // attachment
        //$message->attach(Swift_Attachment::fromPath('/tmp/gaugau.csv'));

        return $message;
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mail_list_id', 'segment_id',
        'subject', 'from_name', 'from_email',
        'reply_to', 'track_open',
        'track_click', 'sign_dkim', 'track_fbl',
        'html', 'plain'
    ];
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'mail_list_uid' => 'required',
    );
    
    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return Campaign::select("campaigns.*");
    }
    
    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions($user = null, $status=null)
    {
        $query = Campaign::getAll();
        if(is_object($user)) {
            $query = $query->where("user_id", "=", $user->id);
        }
        if(isset($status)) {
            $query = $query->where("status", "=", $status);
        }
        $options = $query->orderBy("created_at", "DESC")->get()->map(function ($item) {
            return ['value' => $item->uid, 'text' => $item->name];
        });
        return $options;
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();        
        
        // Create uid when creating list.
        static::creating(function($item)
        {
            // Create new uid
            $uid = uniqid();
            while(Campaign::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
            
            // Update custom order
            Campaign::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
            
            // Update links
            $item->updateLinks();
        });
        
        static::updating(function($item)
        {
            // Update links
            $item->updateLinks();
        });
    }
    
    /**
     * Get current links of campaign.
     *
     * @return void
     */
    public function getLinks()
    {
        return $this->links()->whereIn("url", $this->getUrls())->get();
    }
    
    /**
     * Get urls from campaign html.
     *
     * @return void
     */
    public function getUrls()
    {
        // Find all links in campaign content
        preg_match_all('/(?<=href=)(\'|")?(?<url>.*?)(?=\1|\))/i', $this->html, $matches);
        $hrefs = array_unique($matches['url']);
        
        $urls = [];
        foreach ($hrefs as $href) {
            if (preg_match('/^http/i', $href)) {
                $urls[] = strtolower(trim($href));
            }
        }

        return $urls;
    }
    
    /**
     * Update campaign links.
     *
     * @return void
     */
    public function updateLinks()
    {
        foreach ($this->getUrls() as $url) {
            $link = Link::where("url", "=", $url)->first();
            if (!is_object($link)) {
                $link = new Link();
                $link->url = $url;
                $link->save();
            }
            
            // Campaign link
            if($this->links()->where("url", "=", $url)->count() == 0) {
                $cl = new CampaignLink();
                $cl->campaign_id = $this->id;
                $cl->link_id = $link->id;
                $cl->save();
            }
        }

    }
    
    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return Campaign::where("uid", "=", $uid)->first();
    }
    
    /**
     * Get max step.
     *
     * @return object
     */
    public function step()
    {
        $step = 0;
        
        // Step 1
        if (is_object($this->mailList)) {
            $step = 1;
        } else {
            return $step;
        }
        
        // Step 2
        if (!empty($this->name) && !empty($this->subject) && !empty($this->from_name)
                && !empty($this->from_email) && !empty($this->reply_to)) {
            $step = 2;
        } else {
            return $step;
        }
        
        // Step 3
        if (!empty($this->html) && !empty($this->plain)) {
            $step = 3;
        } else {
            return $step;
        }
        
        // Step 4
        if (isset($this->delivery_at) && $this->delivery_at != '0000-00-00 00:00:00') {
            $step = 4;
        } else {
            return $step;
        }
        
        return $step;
    }
    
    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = Campaign::where("user_id", "=", $user->id);
        
        // Keyword
        if(!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%' . $request->keyword . '%');
        }
        
        return $query;
    }
    
    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = Campaign::filter($request);
        
        $query = $query->orderBy($request->sort_order, $request->sort_direction);
        
        return $query;
    }
    
    /**
     * Subscribers.
     *
     * @return collect
     */
    public function subscribers()
    {
        if (is_object($this->segment)) {
            return $this->segment->subscribers();
        } else if (is_object($this->mailList)) {
            return $this->mailList->subscribers();
        }
        
        return collect([]);
    }
    
    /**
     * Create customer action log
     *
     * @param string $cat
     * @param User $user
     * @param array $add_datas
     * @return void
     */
    public function log($name, $user, $add_datas=[])
    {
        $data = [
                'id' => $this->id,
                'name' => $this->name,
        ];
        
        if (is_object($this->mailList)) {
            $data['list_id'] = $this->mail_list_id;
            $data['list_name'] = $this->mailList->name;
        }
        
        if (is_object($this->segment)) {
            $data['segment_id'] = $this->segment_id;
            $data['segment_name'] = $this->segment->name;
        }
        
        $data = array_merge($data, $add_datas);

        \Mailixa\Model\Log::create([
                                'user_id' => $user->id,
                                'type' => 'campaign',
                                'name' => $name,
                                'data' => json_encode($data),
                            ]);
    }
    
    /**
     * Count delivery processed
     *
     * @return number
     */
    public function trackingCount()
    {
        return $this->trackingLogs()->count();
    }
    
    /**
     * Count delivery processed
     *
     * @return number
     */
    public function deliveredCount()
    {
        return $this->trackingLogs()->where("status", "=", "sent")->count();
    }
    
    /**
     * Count delivery success rate
     *
     * @return number
     */
    public function deliveredRate()
    {
        if ($this->trackingCount() == 0) {
            return 0;
        }
        return round(($this->deliveredCount() / $this->trackingCount())*100, 0);
    }
    
    /**
     * Count delivery error
     *
     * @return number
     */
    public function deliveryErrorCount()
    {
        return $this->deliveryProcessedCount() - $this->delivery_success_count();
    }
    
    /**
     * Count delivery error rate
     *
     * @return number
     */
    public function deliveryErrorRate()
    {
        if ($this->deliveryProcessedCount() == 0) {
            return 0;
        }
        return round(($this->deliveryErrorCount() / $this->deliveryProcessedCount())*100, 0);
    }
    
    /**
     * Count click
     *
     * @return number
     */
    public function clickCount($start=null, $end=null)
    {
        $query = $this->clickLogs();
        if (isset($start)) {
            $query = $query->where('click_logs.created_at', '>=', $start);
        }
        if (isset($end)) {
            $query = $query->where('click_logs.created_at', '<=', $end);
        }
        
        return $query->count();
    }
    
    /**
     * Count click
     *
     * @return number
     */
    public function clickPerUniqOpen()
    {
        if ($this->openCount() == 0) {
            return 0;
        }
        return round(($this->clickCount() / $this->openCount())*100, 0);
    }
    
    /**
     * Count abuse feedback
     *
     * @return number
     */
    public function abuseFeedbackCount()
    {
        return $this->feedbackLogs()->where("feedback_type", "=", "abuse")->count();
    }
    
    /**
     * Count open
     *
     * @return number
     */
    public function openCount()
    {
        return $this->openLogs()->count();
    }
    
    /**
     * Count unique open
     *
     * @return number
     */
    public function openUniqCount($start=null, $end=null)
    {
        $query = $this->openLogs();
        if (isset($start)) {
            $query = $query->where('open_logs.created_at', '>=', $start);
        }
        if (isset($end)) {
            $query = $query->where('open_logs.created_at', '<=', $end);
        }
        
        return $query->distinct('subscriber_id')->count('subscriber_id');
    }
    
    /**
     * Count open rate
     *
     * @return number
     */
    public function openRate()
    {
        if ($this->trackingCount() == 0) {
            return 0;
        }
        return round(($this->openCount() / $this->trackingCount())*100, 0);
    }
    
    /**
     * Count unique open rate
     *
     * @return number
     */
    public function openUniqRate()
    {
        if ($this->trackingCount() == 0) {
            return 0;
        }
        return round(($this->openUniqCount() / $this->trackingCount())*100, 0);
    }
    
    
    /**
     * Count bounce back
     *
     * @return number
     */
    public function bounceCount()
    {
        return $this->campaign_bounce_logs()->count();
    }
    
    /**
     * Count bounce rate
     *
     * @return number
     */
    public function bounceRate()
    {
        if ($this->deliveryProcessedCount() == 0) {
            return 0;
        }
        return round(($this->bounceCount() / $this->deliveryProcessedCount())*100, 0);
    }
    
    /**
     * Count hard bounce
     *
     * @return number
     */
    public function hardBounceCount()
    {
        return $this->campaign_bounce_logs()->where("bounce_type", "=", "hard")->count();
    }
    
    /**
     * Count hard bounce rate
     *
     * @return number
     */
    public function hardBounceRate()
    {
        if ($this->deliveryProcessedCount() == 0) {
            return 0;
        }
        return round(($this->hardBounceCount() / $this->deliveryProcessedCount())*100, 0);
    }
    
    /**
     * Count soft bounce
     *
     * @return number
     */
    public function softBounceCount()
    {
        return $this->campaign_bounce_logs()->where("bounce_type", "=", "soft")->count();
    }
    
    /**
     * Count soft bounce rate
     *
     * @return number
     */
    public function softBounceRate()
    {
        if ($this->deliveryProcessedCount() == 0) {
            return 0;
        }
        return round(($this->softBounceCount() / $this->deliveryProcessedCount())*100, 0);
    }
    
    
    /**
     * Count unsubscibe
     *
     * @return number
     */
    public function unsubscribeCount()
    {
        return $this->campaign_track_unsubscribes()->count();
    }
    
    /**
     * Count unsubscibe rate
     *
     * @return number
     */
    public function unsubscribeRate()
    {
        if ($this->deliveryProcessedCount() == 0) {
            return 0;
        }
        return round(($this->unsubscribeCount() / $this->deliveryProcessedCount())*100, 0);
    }
    
    /**
     * Get last click
     *
     * @param number $number
     * @return collect
     */
    public function lastClick()
    {
        return $this->clickLogs()->orderBy('created_at', 'desc')->first();
    }
    
    /**
     * Get last open
     *
     * @param number $number
     * @return collect
     */
    public function lastOpen()
    {
        return $this->openLogs()->orderBy('created_at', 'desc')->first();
    }
    
    /**
     * Get last open list
     *
     * @param number $number
     * @return collect
     */
    public function lastOpens($number)
    {
        return $this->openLogs()->orderBy('created_at', 'desc')->limit($number);
    }
    
    /**
     * Get most open subscribers
     *
     * @param number $number
     * @return collect
     */
    public function mostOpenSubscribers($number)
    {
        return \Mailixa\Web\Subscriber::selectRaw(\DB::getTablePrefix()."list_subscriber.*, COUNT(".\DB::getTablePrefix()."campaign_track_unsubscribe.id) AS openCount")
                            ->leftJoin('campaign_track_unsubscribe', 'campaign_track_unsubscribe.subscriber_id', '=', 'list_subscriber.subscriber_id')
                            ->where('campaign_track_unsubscribe.campaign_id', '=', $this->campaign_id)
                            ->groupBy('list_subscriber.subscriber_id')
                            ->orderBy('openCount', 'desc')
                            ->limit($number);
    }
    
    /**
     * Get last opened time
     *
     * @return datetime
     */
    public function getLastOpen()
    {
        $last = $this->campaign_track_opens()->orderBy('created_at', 'desc')->first();
        return (is_object($last) ? $last->created_at->timezone(\Auth::user()->timezone) : null);
    }
    
    /**
     * Campaign top 5 opens
     *
     * @return datetime
     */
    public static function topOpens($number=5, $user=null)
    {
        $records = Campaign::select(\DB::raw(\DB::getTablePrefix() . 'campaigns.*, count(*) as `aggregate`'))
            ->join('tracking_logs', 'tracking_logs.campaign_id', '=', 'campaigns.id')
            ->join('open_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id');
        
        if(isset($user)) {
            $records = $records->where('campaigns.user_id', "=", $user->id);
        }    
        
        $records = $records->groupBy('tracking_logs.message_id')
            ->orderBy('aggregate', 'desc');
            
        return $records;
    }
    
    /**
     * Campaign top 5 clicks
     *
     * @return datetime
     */
    public static function topClicks($number=5, $user=null)
    {
        $records = Campaign::select(\DB::raw(\DB::getTablePrefix() . 'campaigns.*, count(*) as `aggregate`'))
            ->join('tracking_logs', 'tracking_logs.campaign_id', '=', 'campaigns.id')
            ->join('click_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id');
        
        if(isset($user)) {
            $records = $records->where('campaigns.user_id', "=", $user->id);
        }
        
        $records = $records->groupBy('tracking_logs.message_id')
                    ->orderBy('aggregate', 'desc');
            
        return $records;
    }
    
    /**
     * Campaign top 5 clicks
     *
     * @return datetime
     */
    public static function topLinks($number=5, $user=null)
    {
        $records = Link::select(\DB::raw(\DB::getTablePrefix() . 'links.*, count(*) as `aggregate`'))
            ->join('campaign_links', 'campaign_links.link_id', '=', 'links.id');            
        
        if(isset($user)) {
            $records = $records->join('campaigns', 'campaign_links.campaign_id', '=', 'campaigns.id')
                ->where('campaigns.user_id', "=", $user->id);
        }
        
        $records = $records->groupBy('campaign_links.link_id')
            ->orderBy('aggregate', 'desc');
            
        return $records;
    }
}


