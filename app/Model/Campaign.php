<?php

/**
 * Campaign class.
 *
 * Model class for campaigns related functionalities.
 * This is the center of the application
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
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Log as MailLog;
use Acelle\Library\StringHelper;
use DB;

class Campaign extends Model
{
    // Campaign status
    const STATUS_NEW = 'new';
    const STATUS_READY = 'ready';
    const STATUS_SENDING = 'sending';
    const STATUS_ERROR = 'error';
    const STATUS_DONE = 'done';

    // Campaign types
    const TYPE_REGULAR = 'regular';
    const TYPE_PLAIN_TEXT = 'plain-text';

    // Campaign settings
    const WORKER_DELAY = 1;
    const DKIM_SELECTOR = 'mailer';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'run_at'];

    public static $required_tags = array(
        array('name' => '{UNSUBSCRIBE_URL}', 'required' => true),
    );

    /**
     * Get the user that owns the phone.
     */
    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    /**
     * Get the segment of the campaign.
     */
    public function segment()
    {
        return $this->belongsTo('Acelle\Model\Segment');
    }

    /**
     * Get the links for campaign.
     */
    public function links()
    {
        return $this->belongsToMany('Acelle\Model\Link', 'campaign_links');
    }

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo('Acelle\Model\User');
    }
    
    /**
     * Get campaign tracking logs.
     *
     * @return mixed
     */
    public function trackingLogs()
    {
        return $this->hasMany('Acelle\Model\TrackingLog');
    }
    
    /**
     * Get campaign bounce logs.
     *
     * @return mixed
     */
    public function bounceLogs()
    {
        return BounceLog::select('bounce_logs.*')->leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'bounce_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);
    }
    
    /**
     * Get campaign open logs.
     *
     * @return mixed
     */
    public function openLogs()
    {
        return OpenLog::select('open_logs.*')->leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'open_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);
    }
    
    /**
     * Get campaign click logs.
     *
     * @return mixed
     */
    public function clickLogs()
    {
        return ClickLog::select('click_logs.*')->leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);
    }
    
    /**
     * Get campaign feedback loop logs.
     *
     * @return mixed
     */
    public function feedbackLogs()
    {
        return FeedbackLog::select('feedback_logs.*')->leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'feedback_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);
    }
    
    /**
     * Get campaign unsubscribe logs.
     *
     * @return mixed
     */
    public function unsubscribeLogs()
    {
        return UnsubscribeLog::select('unsubscribe_logs.*')->leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'unsubscribe_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);
    }

    /**
     * get pending campaigns.
     */
    public static function getWaitingCampaigns()
    {
        return self::where('status', self::STATUS_READY)->whereRaw('(run_at IS NULL OR run_at <= NOW())')->get();
    }

    /**
     * Mark the campaign as 'done' or 'sent'.
     */
    public function done()
    {
        $this->status = self::STATUS_DONE;
        $this->delivery_at = \Carbon\Carbon::now();
        $this->save();
    }

    /**
     * Mark the campaign as 'sending'.
     */
    public function sending()
    {
        $this->status = self::STATUS_SENDING;
        $this->save();
    }

    /**
     * Mark the campaign as 'done' or 'sent'.
     */
    public function error()
    {
        $this->status = self::STATUS_ERROR;
        $this->save();
    }

    /**
     * Mark the campaign as 'done' or 'sent'.
     */
    public function refreshStatus()
    {
        $me = self::find($this->id);
        $this->status = $me->status;
        $this->save();
    }
    
    /**
     * Mark campaign as 'ready'.
     *
     * @return boolean
     */
    public function ready()
    {
        $this->status = self::STATUS_READY;
        $this->save();
    }
    
    /**
     * Get campaign's email chunks.
     *
     * @return mixed
     */
    public function getChunks($collection, $count)
    {
        $result = [];
        if (sizeof($collection) == 0) {
            return $result;
        }
        $chunks = array_chunk(range(0, sizeof($collection) - 1), ceil(sizeof($collection) / (float) $count), true);
        foreach ($chunks as $key => $chunk) {
            $list = [];
            foreach (array_values($chunk) as $i) {
                $list[] = $collection[$i];
            }
            $result[] = $list;
        }

        return $result;
    }

    /**
     * Start the campaign, using PHP fork() to launch multiple processes.
     *
     * @return mixed
     */
    public function startMultiProcesses()
    {
        $this->sending();

        MailLog::info("Starting campaign `{$this->name}` (ID: {$this->id})");
        $count = (int) $this->user->getOption('frontend', 'max_process');
        $subscribers = $this->getPendingSubscribers();
        MailLog::info(sizeof($subscribers).' subscriber(s) selected');
        $chunks = $this->getChunks($subscribers, $count);

        MailLog::info('Start multi-process ('.sizeof($chunks).') session');
        $parentPid = getmypid();
        for ($i = 0; $i < sizeof($chunks); ++$i) {
            $pid = pcntl_fork();

            if (!$pid) {
                // Reconnect to the DB to prevent connection closed issue when using fork
                DB::reconnect('mysql');
                // Re-initialize logging to capture the child process' PID
                MailLog::fork();

                MailLog::info('Start child process '.($i + 1).' of '.sizeof($chunks).' (forked from '.$parentPid.')');
                sleep(self::WORKER_DELAY);
                $this->start($chunks[$i], true);
                exit($i + 1);
            }
        }

        while (pcntl_waitpid(0, $status) != -1) {
            $status = pcntl_wexitstatus($status);
            MailLog::info("Child $status completed\n");
        }

        $this->refreshStatus();

        if ($this->status == self::STATUS_SENDING) {
            $this->done();
            MailLog::info('Campaign sent!');
        } else {
            MailLog::warning('Campaign sent, but some errors occurred!');
        }
    }

    /**
     * Send Campaign
     * Iterate through all subscribers and send email.
     */
    public function start($subscribers = null, $multi = false)
    {
        try {
            if (!$multi) {
                $this->sending();
                MailLog::info("Starting campaign `{$this->name}` (ID: {$this->id})");
                $subscribers = $this->getPendingSubscribers();
                MailLog::info(sizeof($subscribers).' subscriber(s) selected');
            }

            $i = 0;
            foreach ($subscribers as $subscriber) {
                if ($this->user->overQuota()) {
                    throw new \Exception("Customer (ID: {$this->user->id}) has reached sending quota");
                }

                $i += 1;
                MailLog::info("Sending to subscriber `{$subscriber->email}` ({$i}/".sizeof($subscribers).')');

                $server = $this->pickServer($this);
                list($message, $msgId) = $this->prepareEmail($subscriber, $server);

                $sent = $server->send($message, array(
                    'from_email' => $this->from_email,
                    'to' => $subscriber->email, // workaround: some sending engine requires the TO explicitly (not in MIME message)
                    'campaign' => $this,
                    'subscriber' => $subscriber,
                    'msgId' => $msgId
                ));

                $this->trackMessage($sent, $subscriber, $server, $msgId);
            }

            if (!$multi) {
                $this->done();
                MailLog::info('Campaign sent!');
            }
        } catch (\Exception $e) {
            MailLog::error($e->getMessage());
            MailLog::error("Campaign `{$this->name}` (ID: ".$this->id.') deferred!');
            $this->error();
        } finally {
            // reset server pools: just in case DeliveryServerAmazonSesWebApi
            // --> setup SNS requires using from_email of the corresponding campaign
            // but SNS is only made once when the server is initiated
            SendingServer::resetServerPools();
        }
    }

    /**
     * Log Delivery
     * Log delivery message, used for later tracking.
     */
    private function trackMessage($response, $subscriber, $server, $msgId)
    {
        $params = array_merge(array(
                'campaign_id' => $this->id,
                'message_id' => $msgId,
                'subscriber_id' => $subscriber->id,
                'sending_server_id' => $server->id,
                'user_id' => $this->user->id,
            ), $response);

        if (!isset($params['runtime_message_id'])) {
            $params['runtime_message_id'] = $msgId;
        }

        return TrackingLog::create($params);
    }
    
    /**
     * Pick up a delivery server for the campaign.
     *
     * @return mixed
     */
    public function pickServer($campaign)
    {
        return SendingServer::pickServer($campaign);
    }

    /**
     * Template tags.
     *
     * All availabel template tags
     */
    public function tags()
    {
        $tags = [];

        // List field tags
        foreach ($this->mailList->fields as $field) {
            $tags[] = ['name' => 'SUBSCRIBER_'.$field->tag, 'required' => false];
        }

        $tags = array_merge($tags, [
            ['name' => 'UNSUBSCRIBE_URL', 'required' => (\Auth::user()->getOption('frontend', 'unsubscribe_url_required') == 'yes' ? true : false)],
            ['name' => 'SUBSCRIBER_UID', 'required' => false],
            ['name' => 'CAMPAIGN_NAME', 'required' => false],
            ['name' => 'CAMPAIGN_UID', 'required' => false],
            ['name' => 'CAMPAIGN_SUBJECT', 'required' => false],
            ['name' => 'CAMPAIGN_FROM_EMAIL', 'required' => false],
            ['name' => 'CAMPAIGN_FROM_NAME', 'required' => false],
            ['name' => 'CAMPAIGN_REPLY_TO', 'required' => false],
            ['name' => 'CURRENT_YEAR', 'required' => false],
            ['name' => 'CURRENT_MONTH', 'required' => false],
            ['name' => 'CURRENT_DAY', 'required' => false],
            ['name' => 'CONTACT_NAME', 'required' => false],
            ['name' => 'CONTACT_COUNTRY', 'required' => false],
            ['name' => 'CONTACT_STATE', 'required' => false],
            ['name' => 'CONTACT_CITY', 'required' => false],
            ['name' => 'CONTACT_ADDRESS_1', 'required' => false],
            ['name' => 'CONTACT_ADDRESS_2', 'required' => false],
            ['name' => 'CONTACT_PHONE', 'required' => false],
            ['name' => 'CONTACT_URL', 'required' => false],
            ['name' => 'CONTACT_EMAIL', 'required' => false],
            ['name' => 'LIST_NAME', 'required' => false],
            ['name' => 'LIST_SUBJECT', 'required' => false],
            ['name' => 'LIST_FROM_NAME', 'required' => false],
            ['name' => 'LIST_FROM_EMAIL', 'required' => false],
        ]);

        return $tags;
    }

    /**
     * Transform Tags
     * Transform tags to actual values before sending.
     */
    private function tagMessage($message, $subscriber, $msgId)
    {
        $tags = array(
            'SUBSCRIBER_EMAIL' => $subscriber->email,
            'CAMPAIGN_NAME' => $this->name,
            'CAMPAIGN_UID' => $this->uid,
            'CAMPAIGN_SUBJECT' => $this->subject,
            'CAMPAIGN_FROM_EMAIL' => $this->from_email,
            'CAMPAIGN_FROM_NAME' => $this->from_name,
            'CAMPAIGN_REPLY_TO' => $this->reply_to,
            'SUBSCRIBER_UID' => $subscriber->uid,
            'CURRENT_YEAR' => date('Y'),
            'CURRENT_MONTH' => date('m'),
            'CURRENT_DAY' => date('d'),
            'UNSUBSCRIBE_URL' => str_replace('MESSAGE_ID', StringHelper::base64UrlEncode($msgId), Setting::get('url_unsubscribe')),
            'CONTACT_NAME' => $this->mailList->contact->company,
            'CONTACT_COUNTRY' => $this->mailList->contact->country->name,
            'CONTACT_STATE' => $this->mailList->contact->state,
            'CONTACT_CITY' => $this->mailList->contact->city,
            'CONTACT_ADDRESS_1' => $this->mailList->contact->address_1,
            'CONTACT_ADDRESS_2' => $this->mailList->contact->address_2,
            'CONTACT_PHONE' => $this->mailList->contact->phone,
            'CONTACT_URL' => $this->mailList->contact->url,
            'CONTACT_EMAIL' => $this->mailList->contact->email,
            'LIST_NAME' => $this->mailList->name,
            'LIST_SUBJECT' => $this->mailList->default_subject,
            'LIST_FROM_NAME' => $this->mailList->from_name,
            'LIST_FROM_EMAIL' => $this->mailList->from_email,
        );

        foreach ($tags as $tag => $value) {
            $message = str_replace('{'.$tag.'}', $value, $message);
        }

        // List field tags
        foreach ($this->mailList->fields as $field) {
            $message = str_replace('{SUBSCRIBER_'.$field->tag.'}', $subscriber->getValueByField($field), $message);
        }

        return $message;
    }

    /**
     * Get Pending Subscribers
     * Select only subscribers that are ready for sending. Those whose status is `blacklisted`, `pending` or `unconfirmed` are not included.
     */
    public function getPendingSubscribers()
    {
        if (is_object($this->segment)) {
            $scoped = $this->segment;
        } else {
            $scoped = $this->mailList;
        }

        return $scoped->subscribers()
                ->leftJoin(DB::raw('(SELECT * FROM '.DB::getTablePrefix().'tracking_logs WHERE campaign_id = '.$this->id.') log'), 'subscribers.id', '=', 'subscriber_id')
                ->whereRaw('log.id IS NULL')
                ->whereRaw(DB::getTablePrefix()."subscribers.status = '".Subscriber::STATUS_SUBSCRIBED."'")
                ->select('subscribers.*')
                ->get();
    }

    /**
     * Append Open Tracking URL
     * Append open-tracking URL to every email message.
     */
    public function appendOpenTrackingUrl($body, $msgId)
    {
        $openTrackingBaseURL = Setting::get('url_open_track');
        $tracking_url = str_replace('MESSAGE_ID', StringHelper::base64UrlEncode($msgId), $openTrackingBaseURL);

        return $body.'<img src="'.$tracking_url.'" width="0" height="0" style="visibility:hidden" />';
    }

    /**
     * Build Email Custom Headers
     *
     * @return Hash list of custom headers
     */
    public function getCustomHeaders($subscriber, $server) {
        $msgId = StringHelper::generateMessageId(StringHelper::getDomainFromEmail($this->from_email));

        return array(
            'X-Acelle-Campaign-Id' => $this->uid,
            'X-Acelle-Subscriber-Id' => $subscriber->uid,
            'X-Acelle-User-Id' => $this->user->uid,
            'X-Acelle-Message-Id' => $msgId,
            'X-Acelle-Sending-Server-Id' => $server->uid,
            'List-Unsubscribe' => '<'.str_replace('MESSAGE_ID', StringHelper::base64UrlEncode($msgId), Setting::get('url_unsubscribe')).'>',
            'Precedence' => 'bulk'
        );
    }
    
    /**
     * Build Email HTML content
     *
     * @return String
     */
    public function getHtmlContent($subscriber, $msgId) {
        // @note: IMPORTANT: the order must be
        // * addTrackingURL
        // * appendOpenTrackingUrl
        // * tagMessage

        // @note: addTrackingUrl() must go before appendOpenTrackingUrl()
        $body = $this->html;
        if ($this->track_click) {
            $body = $this->addTrackingUrl($body, $msgId);
        }
        
        if ($this->track_open) {
            $body = $this->appendOpenTrackingUrl($body, $msgId);
        }
        
        $body = $this->tagMessage($body, $subscriber, $msgId);

        return $body;
    }

    /**
     * Build Email HTML content
     *
     * @return String
     */
    public function getPlainContent($subscriber, $msgId) {
        $plain = $this->tagMessage($this->plain, $subscriber, $msgId);

        return $plain;
    }

    /**
     * Prepare Email
     * Prepare the email content using Swift Mailer.
     *
     * @input Raw email content
     *
     * @return MIME text message
     */
    private function prepareEmail($subscriber, $server)
    {
        // build the message
        $customHeaders = $this->getCustomHeaders($subscriber, $this);
        $msgId = $customHeaders['X-Acelle-Message-Id'];

        $message = \Swift_Message::newInstance();
        $message->setId($msgId);
        
        if ($this->type == self::TYPE_REGULAR) {
            $message->setContentType('text/html; charset=utf-8');
        } else {
            $message->setContentType('text/plain; charset=utf-8');
        }

        foreach($customHeaders as $key => $value) {
            $message->getHeaders()->addTextHeader($key, $value);
        }

        // @TODO for AWS, setting returnPath requires verified domain or email address
        //$message->setReturnPath($server->getVerp($subscriber->email));
        $message->setSubject($this->subject);
        $message->setFrom(array($this->from_email => $this->from_name));
        $message->setTo($subscriber->email);
        $message->setReplyTo($this->reply_to);
        $message->setEncoder(\Swift_Encoding::get8bitEncoding());
        $message->setBody($this->getPlainContent($subscriber, $msgId), 'text/plain');
        if ($this->type == self::TYPE_REGULAR) {
            $message->addPart($this->getHtmlContent($subscriber, $msgId), 'text/html');
        }
        
        if ($this->sign_dkim) {
            $message = $this->sign($message);
        }

        // attachment
        //$message->attach(Swift_Attachment::fromPath('/tmp/gaugau.csv'));
        return array($message, $msgId);
    }

    /**
     * Find sending domain from email.
     *
     * @return mixed
     */
    private function findSendingDomain($email)
    {
        $domain = substr(strrchr($email, '@'), 1);

        return SendingDomain::where('name', $domain)->first();
    }
    
    /**
     * Sign the message with DKIM.
     *
     * @return mixed
     */
    private function sign($message)
    {
        $sendingDomain = $this->findSendingDomain($this->from_email);

        if (empty($sendingDomain)) {
            return $message;
        }

        $privateKey = $sendingDomain->dkim_private;
        $domainName = $sendingDomain->name;
        $selector = self::DKIM_SELECTOR;
        $signer = new \Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
        $signer->ignoreHeader('Return-Path');
        $message->attachSigner($signer);

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
        'html', 'plain',
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
        return self::select('campaigns.*');
    }

    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions($user = null, $status = null)
    {
        $query = self::getAll();
        if (is_object($user)) {
            $query = $query->where('user_id', '=', $user->id);
        }
        if (isset($status)) {
            $query = $query->where('status', '=', $status);
        }
        $options = $query->orderBy('created_at', 'DESC')->get()->map(function ($item) {
            return ['value' => $item->uid, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (Campaign::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;

            // Update custom order
            Campaign::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;

            // Update links
            $item->updateLinks();
        });

        static::updating(function ($item) {
            // Update links
            $item->updateLinks();
        });
    }

    /**
     * Get current links of campaign.
     */
    public function getLinks()
    {
        return $this->links()->whereIn('url', $this->getUrls())->get();
    }

    /**
     * Get urls from campaign html.
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
     */
    public function updateLinks()
    {
        foreach ($this->getUrls() as $url) {
            $link = Link::where('url', '=', $url)->first();
            if (!is_object($link)) {
                $link = new Link();
                $link->url = $url;
                $link->save();
            }

            // Campaign link
            if ($this->links()->where('url', '=', $url)->count() == 0) {
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
        return self::where('uid', '=', $uid)->first();
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
        if ((!empty($this->html) || $this->type == 'plain-text') && !empty($this->plain)) {
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
        $query = self::where('user_id', '=', $user->id);

        // Keyword
        if (!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%'.$request->keyword.'%');
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
        $query = self::filter($request);

        $query = $query->orderBy($request->sort_order, $request->sort_direction);

        return $query;
    }

    /**
     * Subscribers.
     *
     * @return collect
     */
    public function subscribers($params=[])
    {
        if(!is_object($this->segment) && !is_object($this->mailList)) {
            return collect([]);
        }
        
        if (is_object($this->segment)) {
            $query = $this->segment->subscribers();
        } elseif (is_object($this->mailList)) {
            $query = $this->mailList->subscribers();
        }
        
        $query = $query->select("subscribers.*");
        
        // Filter
        $filters = isset($params["filters"]) ? $params["filters"] : null;
        if(isset($filters)) {
            
            if(isset($filters["open"]) || isset($filters["click"]) || isset($filters["tracking_status"])) {
                $query = $query->leftJoin('tracking_logs', 'tracking_logs.subscriber_id', '=', 'subscribers.id');
            }
            
            if(isset($filters["open"])) {
                $equal = ($filters["open"] == "opened") ? "!=" : "=";                
                $query = $query->leftJoin('open_logs', 'tracking_logs.message_id', '=', 'open_logs.message_id')
                    ->where('open_logs.id', $equal, null);
            }
            if(isset($filters["click"])) {
                $equal = ($filters["click"] == "clicked") ? "!=" : "=";
                $query = $query->leftJoin('click_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
                    ->where('click_logs.id', $equal, null);
            }
            if(isset($filters["tracking_status"])) {
                $val = ($filters["tracking_status"] == "not_sent") ? null : $filters["tracking_status"];
                $query = $query->where('tracking_logs.status', "=", $val);
            }
        }
        
        // keyword
        if (isset($params["keyword"]) && !empty(trim($params["keyword"]))) {
            foreach (explode(' ', trim($params["keyword"])) as $keyword) {
                $query = $query->leftJoin('subscriber_fields', 'subscribers.id', '=', 'subscriber_fields.subscriber_id');
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('subscribers.email', 'like', '%'.$keyword.'%')
                        ->orWhere('subscriber_fields.value', 'like', '%'.$keyword.'%');
                });
            }
        }

        return $query->distinct();
    }

    /**
     * Create customer action log.
     *
     * @param string $cat
     * @param User   $user
     * @param array  $add_datas
     */
    public function log($name, $user, $add_datas = [])
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

        \Acelle\Model\Log::create([
                                'user_id' => $user->id,
                                'type' => 'campaign',
                                'name' => $name,
                                'data' => json_encode($data),
                            ]);
    }

    /**
     * Count delivery processed.
     *
     * @return number
     */
    public function trackingCount()
    {
        return $this->trackingLogs()->count();
    }

    /**
     * Count delivery processed.
     *
     * @return number
     */
    public function deliveredCount()
    {
        return $this->trackingLogs()->where('status', '=', 'sent')->count();
    }
    
    /**
     * Count failed processed.
     *
     * @return number
     */
    public function failedCount()
    {
        return $this->trackingLogs()->where('status', '=', 'failed')->count();
    }

    /**
     * Count delivery success rate.
     *
     * @return number
     */
    public function deliveredRate()
    {
        if ($this->trackingCount() == 0) {
            return 0;
        }

        return round(($this->deliveredCount() / $this->trackingCount()) * 100, 0);
    }

    /**
     * Count click.
     *
     * @return number
     */
    public function clickCount($start = null, $end = null)
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
     * Url count.
     *
     * @return number
     */
    public function urlCount()
    {
        return $this->links()->count();
    }
    
    /**
     * Click rate.
     *
     * @return number
     */
    public function clickedLinkCount()
    {
        return $this->clickLogs()->distinct('url')->count('url');
    }

    /**
     * Click rate.
     *
     * @return number
     */
    public function clickRate()
    {
        if ($this->urlCount() == 0) {
            return 0;
        }

        return round(($this->clickedLinkCount() / $this->urlCount()) * 100, 0);
    }
    
    /**
     * Click a link rate.
     *
     * @return number
     */
    public function clickALinkRate()
    {
        if ($this->openCount() == 0) {
            return 0;
        }

        return round(($this->clickCount() / $this->openCount()) * 100, 0);
    }

    /**
     * Count click.
     *
     * @return number
     */
    public function clickPerUniqOpen()
    {
        if ($this->openCount() == 0) {
            return 0;
        }

        return round(($this->clickCount() / $this->openCount()) * 100, 0);
    }

    /**
     * Count abuse feedback.
     *
     * @return number
     */
    public function abuseFeedbackCount()
    {
        return $this->feedbackLogs()->where('feedback_type', '=', 'abuse')->count();
    }

    /**
     * Count open.
     *
     * @return number
     */
    public function openCount()
    {
        return $this->openLogs()->count();
    }
    
    /**
     * Not open count.
     *
     * @return number
     */
    public function notOpenCount()
    {
        return $this->subscribers()->count() - $this->openUniqCount();
    }

    /**
     * Count unique open.
     *
     * @return number
     */
    public function openUniqCount($start = null, $end = null)
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
     * Open rate.
     *
     * @return number
     */
    public function openRate()
    {
        if ($this->deliveredCount() == 0) {
            return 0;
        }

        return round(($this->openCount() / $this->deliveredCount()) * 100, 0);
    }
    
    /**
     * Not open rate.
     *
     * @return number
     */
    public function notOpenRate()
    {
        if ($this->subscribers()->count() == 0) {
            return 0;
        }

        return round(($this->notOpenCount() / $this->subscribers()->count()) * 100, 0);
    }

    /**
     * Count unique open rate.
     *
     * @return number
     */
    public function openUniqRate()
    {
        if ($this->deliveredCount() == 0) {
            return 0;
        }

        return round(($this->openUniqCount() / $this->deliveredCount()) * 100, 0);
    }

    /**
     * Count bounce back.
     *
     * @return number
     */
    public function feedbackCount()
    {
        return $this->feedbackLogs()->distinct('subscriber_id')->count('subscriber_id');
    }
    
    /**
     * Count feedback rate.
     *
     * @return number
     */
    public function feedbackRate()
    {
        if ($this->deliveredCount() == 0) {
            return 0;
        }

        return round(($this->feedbackCount() / $this->deliveredCount()) * 100, 0);
    }

    /**
     * Count bounce back.
     *
     * @return number
     */
    public function bounceCount()
    {
        return $this->bounceLogs()->distinct('subscriber_id')->count('subscriber_id');
    }

    /**
     * Count bounce rate.
     *
     * @return number
     */
    public function bounceRate()
    {
        if ($this->deliveredCount() == 0) {
            return 0;
        }

        return round(($this->bounceCount() / $this->deliveredCount()) * 100, 0);
    }

    /**
     * Count hard bounce.
     *
     * @return number
     */
    public function hardBounceCount()
    {
        return $this->campaign_bounce_logs()->where('bounce_type', '=', 'hard')->distinct('subscriber_id')->count('subscriber_id');
    }

    /**
     * Count hard bounce rate.
     *
     * @return number
     */
    public function hardBounceRate()
    {
        if ($this->deliveryProcessedCount() == 0) {
            return 0;
        }

        return round(($this->hardBounceCount() / $this->deliveryProcessedCount()) * 100, 0);
    }

    /**
     * Count soft bounce.
     *
     * @return number
     */
    public function softBounceCount()
    {
        return $this->campaign_bounce_logs()->where('bounce_type', '=', 'soft')->distinct('subscriber_id')->count('subscriber_id');
    }

    /**
     * Count soft bounce rate.
     *
     * @return number
     */
    public function softBounceRate()
    {
        if ($this->trackingCount() == 0) {
            return 0;
        }

        return round(($this->softBounceCount() / $this->trackingCount()) * 100, 0);
    }

    /**
     * Count unsubscibe.
     *
     * @return number
     */
    public function unsubscribeCount()
    {
        return $this->unsubscribeLogs()->distinct('subscriber_id')->count('subscriber_id');
    }

    /**
     * Count unsubscibe rate.
     *
     * @return number
     */
    public function unsubscribeRate()
    {
        if ($this->deliveredCount() == 0) {
            return 0;
        }

        return round(($this->unsubscribeCount() / $this->deliveredCount()) * 100, 0);
    }

    /**
     * Get last click.
     *
     * @param number $number
     *
     * @return collect
     */
    public function lastClick()
    {
        return $this->clickLogs()->orderBy('created_at', 'desc')->first();
    }

    /**
     * Get last open.
     *
     * @param number $number
     *
     * @return collect
     */
    public function lastOpen()
    {
        return $this->openLogs()->orderBy('created_at', 'desc')->first();
    }

    /**
     * Get last open list.
     *
     * @param number $number
     *
     * @return collect
     */
    public function lastOpens($number)
    {
        return $this->openLogs()->orderBy('created_at', 'desc')->limit($number);
    }

    /**
     * Get most open subscribers.
     *
     * @param number $number
     *
     * @return collect
     */
    public function mostOpenSubscribers($number)
    {
        return \Acelle\Web\Subscriber::selectRaw(\DB::getTablePrefix().'list_subscriber.*, COUNT('.\DB::getTablePrefix().'campaign_track_unsubscribe.id) AS openCount')
                            ->leftJoin('campaign_track_unsubscribe', 'campaign_track_unsubscribe.subscriber_id', '=', 'list_subscriber.subscriber_id')
                            ->where('campaign_track_unsubscribe.campaign_id', '=', $this->campaign_id)
                            ->groupBy('list_subscriber.subscriber_id')
                            ->orderBy('openCount', 'desc')
                            ->limit($number);
    }

    /**
     * Get last opened time.
     *
     * @return datetime
     */
    public function getLastOpen()
    {
        $last = $this->campaign_track_opens()->orderBy('created_at', 'desc')->first();

        return is_object($last) ? $last->created_at->timezone(\Auth::user()->timezone) : null;
    }

    /**
     * Campaign top 5 opens.
     *
     * @return datetime
     */
    public static function topOpens($number = 5, $user = null)
    {
        $records = self::select(\DB::raw(\DB::getTablePrefix().'campaigns.*, count(*) as `aggregate`'))
            ->join('tracking_logs', 'tracking_logs.campaign_id', '=', 'campaigns.id')
            ->join('open_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id');

        if (isset($user)) {
            $records = $records->where('campaigns.user_id', '=', $user->id);
        }

        $records = $records->groupBy('campaigns.id')
            ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }

    /**
     * Campaign top 5 clicks.
     *
     * @return datetime
     */
    public static function topClicks($number = 5, $user = null)
    {
        $records = self::select(\DB::raw(\DB::getTablePrefix().'campaigns.*, count(*) as `aggregate`'))
            ->join('tracking_logs', 'tracking_logs.campaign_id', '=', 'campaigns.id')
            ->join('click_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id');

        if (isset($user)) {
            $records = $records->where('campaigns.user_id', '=', $user->id);
        }

        $records = $records->groupBy('campaigns.id')
                    ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }

    /**
     * Campaign top 5 clicks.
     *
     * @return datetime
     */
    public static function topLinks($number = 5, $user = null)
    {
        $records = Link::select(\DB::raw(\DB::getTablePrefix().'links.*, count(*) as `aggregate`'))
            ->join('campaign_links', 'campaign_links.link_id', '=', 'links.id')
            ->join('tracking_logs', 'tracking_logs.campaign_id', '=', 'campaign_links.campaign_id')
            ->join('click_logs', function ($join) {
                $join->on('click_logs.message_id', '=', 'tracking_logs.message_id')
                ->on('click_logs.url', '=', 'links.url');
            });

        if (isset($user)) {
            $records = $records->join('campaigns', 'campaign_links.campaign_id', '=', 'campaigns.id')
                ->where('campaigns.user_id', '=', $user->id);
        }

        $records = $records->groupBy('links.id')
            ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }

    /**
     * Campaign top 5 clicks.
     *
     * @return datetime
     */
    public function getTopLinks($number = 5)
    {
        $records = ClickLog::select(\DB::raw(\DB::getTablePrefix().'click_logs.*, count(*) as `aggregate`'))
            ->leftJoin('tracking_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);
        
        $records = $records->groupBy('click_logs.url')
            ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }

    /**
     * Campaign top 5 clicks.
     *
     * @return datetime
     */
    public function getTopOpenSubscribers($number = 5)
    {
        $records = Subscriber::select(\DB::raw(\DB::getTablePrefix().'subscribers.*, count(*) as `aggregate`'))
            ->join('tracking_logs', 'tracking_logs.subscriber_id', '=', 'subscribers.id')
            ->join('open_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
            ->where('campaign_id', '=', $this->id);

        $records = $records->groupBy('tracking_logs.message_id')
            ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }
    
    /**
     * Recent subscriber opens.
     *
     * @return datetime
     */
    public function getRecentOpenSubscribers($number = 5)
    {
        $records = Subscriber::select(\DB::raw(\DB::getTablePrefix().'subscribers.*, count(*) as `aggregate`'))
            ->join('tracking_logs', 'tracking_logs.subscriber_id', '=', 'subscribers.id')
            ->join('open_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
            ->where('campaign_id', '=', $this->id);

        $records = $records->groupBy('tracking_logs.message_id')
            ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }    

    /**
     * Campaign top 5 open location.
     *
     * @return datetime
     */
    public function topLocations($number = 5, $user = null)
    {
        $records = IpLocation::select(\DB::raw(\DB::getTablePrefix().'ip_locations.*, count(*) as `aggregate`'))
            ->join('open_logs', 'open_logs.ip_address', '=', 'ip_locations.ip_address')
            ->join('tracking_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);

        if (isset($user)) {
            $records = $records->join('campaigns', 'tracking_logs.campaign_id', '=', 'campaigns.id')
                ->where('campaigns.user_id', '=', $user->id);
        }

        $records = $records->groupBy('open_logs.ip_address')
            ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }
    
    /**
     * Campaign top 5 open countries.
     *
     * @return datetime
     */
    public function topCountries($number = 5, $user = null)
    {
        $records = IpLocation::select(\DB::raw(\DB::getTablePrefix().'ip_locations.*, count(*) as `aggregate`'))
            ->join('open_logs', 'open_logs.ip_address', '=', 'ip_locations.ip_address')
            ->join('tracking_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);

        if (isset($user)) {
            $records = $records->join('campaigns', 'tracking_logs.campaign_id', '=', 'campaigns.id')
                ->where('campaigns.user_id', '=', $user->id);
        }

        $records = $records->groupBy('ip_locations.country_name')
            ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }
    
    /**
     * Campaign top 5 click countries.
     *
     * @return datetime
     */
    public function topClickCountries($number = 5, $user = null)
    {
        $records = IpLocation::select(\DB::raw(\DB::getTablePrefix().'ip_locations.*, count(*) as `aggregate`'))
            ->join('click_logs', 'click_logs.ip_address', '=', 'ip_locations.ip_address')
            ->join('tracking_logs', 'click_logs.message_id', '=', 'tracking_logs.message_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);

        if (isset($user)) {
            $records = $records->join('campaigns', 'tracking_logs.campaign_id', '=', 'campaigns.id')
                ->where('campaigns.user_id', '=', $user->id);
        }

        $records = $records->groupBy('ip_locations.country_name')
            ->orderBy('aggregate', 'desc');

        return $records->take($number);
    }

    /**
     * Campaign locations.
     *
     * @return datetime
     */
    public function locations()
    {
        $records = IpLocation::select('ip_locations.*', 'open_logs.created_at as open_at', 'subscribers.email as email')
            ->leftJoin('open_logs', 'open_logs.ip_address', '=', 'ip_locations.ip_address')
            ->leftJoin('tracking_logs', 'open_logs.message_id', '=', 'tracking_logs.message_id')
            ->leftJoin('subscribers', 'subscribers.id', '=', 'tracking_logs.subscriber_id')
            ->where('tracking_logs.campaign_id', '=', $this->id);

        return $records;
    }

    /**
     * Replace link in text by click tracking url.
     *
     * @return text
     * @note addTrackingUrl() must go before appendOpenTrackingUrl()
     */
    public function addTrackingUrl($email_html_content, $msgId)
    {
        if (preg_match_all('|<a.*(?=href=["\']([^"\']*)["\'])[^>]*>|i', $email_html_content, $matches)) {
            foreach ($matches[0] as $i => $tag) {
                $href = $matches[1][$i];
                $newHref = str_replace('URL', StringHelper::base64UrlEncode($href), Setting::get('url_click_track'));
                $newHref = str_replace('MESSAGE_ID', StringHelper::base64UrlEncode($msgId), $newHref);

                // if the link contains UNSUBSCRIBE URL tag
                if (strpos($href, '{UNSUBSCRIBE_URL}') !== false) {
                    $newTag = preg_replace('/(?<=href=["\'])[^"\']*/', '{UNSUBSCRIBE_URL}', $tag);
                } else {
                    $newTag = preg_replace('/(?<=href=["\'])[^"\']*/', $newHref, $tag);
                }
                $email_html_content = str_replace($tag, $newTag, $email_html_content);
            }
        }

        return $email_html_content;
    }

    /**
     * Type of campaigns.
     *
     * @return object
     */
    public static function types()
    {
        return [
            'regular' => [],
            'plain-text' => [],
        ];
    }
}
