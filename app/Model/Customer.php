<?php

/**
 * Customer class.
 *
 * Model class for customer
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

use Acelle\Library\QuotaTrackerFile;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Acelle\Library\Log as MailLog;

class Customer extends Model
{
    protected $quotaTracker;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'timezone', 'language_id', 'color_scheme'
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        return array(
            'email' => 'required|email|unique:users,email,'.$this->user_id.',id',
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
            'password' => 'confirmed|min:5',
        );
    }
    
    /**
     * Customer email.
     *
     * @return string
     */
    public function email()
    {
        return (is_object($this->user) ? $this->user->email : "" );
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
     * Associations.
     *
     * @var object | collect
     */
    public function contact()
    {
        return $this->belongsTo('Acelle\Model\Contact');
    }

    public function user()
    {
        return $this->belongsTo('Acelle\Model\User');
    }
    
    public function admin()
    {
        return $this->belongsTo('Acelle\Model\Admin');
    }

    public function lists()
    {
        return $this->hasMany('Acelle\Model\MailList')->orderBy('created_at', 'desc');
    }

    public function language()
    {
        return $this->belongsTo('Acelle\Model\Language');
    }

    public function campaigns()
    {
        return $this->hasMany('Acelle\Model\Campaign')->orderBy('created_at', 'desc');
    }

    public function sentCampaigns()
    {
        return $this->hasMany('Acelle\Model\Campaign')->where('status', '=', 'done')->orderBy('created_at', 'desc');
    }
    
    public function subscribers()
    {
        return $this->hasManyThrough('Acelle\Model\Subscriber', 'Acelle\Model\MailList');
    }
    
    public function logs()
    {
        return $this->hasMany('Acelle\Model\Log')->orderBy('created_at', 'desc');
    }
    
    public function trackingLogs()
    {
        return $this->hasMany('Acelle\Model\TrackingLog')->orderBy('created_at', 'asc');
    }
    
    public function automations()
    {
        return $this->hasMany('Acelle\Model\Automation');
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
            while (Customer::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }
    
    /**
     * Display customer name: first_name last_name.
     *
     * @var string
     */
    public function displayName()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Upload and resize avatar.
     *
     * @var void
     */
    public function uploadImage($file)
    {
        $path = 'app/customers/';
        $upload_path = storage_path($path);
        
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        $filename = 'avatar-'.$this->id.'.'.$file->getClientOriginalExtension();

        // save to server
        $file->move($upload_path, $filename);

        // create thumbnails
        $img = \Image::make($upload_path.$filename);
        $img->fit(120, 120)->save($upload_path.$filename.'.thumb.jpg');

        return $path.$filename;
    }

    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function imagePath()
    {
        if (!empty($this->image) && !empty($this->id)) {
            return storage_path($this->image).'.thumb.jpg';
        } else {
            return '';
        }
    }

    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function removeImage()
    {
        if (!empty($this->image) && !empty($this->id)) {
            $path = storage_path($this->image);
            if (is_file($path)) {
                unlink($path);
            }
            if (is_file($path.'.thumb.jpg')) {
                unlink($path.'.thumb.jpg');
            }
        }
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return Customer::select('customers.*');
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $query = self::select('customers.*')
            ->leftJoin('users', 'users.id', '=', 'customers.user_id');
        
        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('customers.first_name', 'like', '%'.$keyword.'%')
                        ->orWhere('customers.last_name', 'like', '%'.$keyword.'%')
                        ->orWhere('users.email', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            
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
     * Subscribers count by time.
     *
     * @return number
     */
    public static function subscribersCountByTime($begin, $end, $customer_id = null, $list_id = null)
    {
        $query = \Acelle\Model\Subscriber::leftJoin('mail_lists', 'mail_lists.id', '=', 'subscribers.mail_list_id')
                                ->leftJoin('customers', 'customers.id', '=', 'mail_lists.customer_id');
    
        if (isset($list_id)) {
            $query = $query->where('subscribers.mail_list_id', '=', $list_id);
        }
        if (isset($customer_id)) {
            $query = $query->where('customers.id', '=', $customer_id);
        }
    
        $query = $query->where('subscribers.created_at', '>=', $begin)
                        ->where('subscribers.created_at', '<=', $end);
    
        return $query->count();
    }
    
    /**
     * Get customer setting.
     *
     * @return string
     */
    public function getOption($name)
    {
        //@todo how to get option from subscription/plan
        return Plan::defaultOptions()[$name];
    }
    
    /**
     * Get max list quota.
     *
     * @return number
     */
    public function maxLists()
    {
        $count = $this->getOption('list_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }
    
    /**
     * Count customer lists.
     *
     * @return number
     */
    public function listsCount()
    {
        return $this->lists()->count();
    }
    
    /**
     * Calculate list usage.
     *
     * @return number
     */
    public function listsUsage()
    {
        if ($this->maxLists() == '∞') {
            return 0;
        }
        if ($this->maxLists() == 0) {
            return 100;
        }
    
        return round((($this->listsCount() / $this->maxLists()) * 100), 2);
    }
    
    /**
     * Display calculate list usage.
     *
     * @return number
     */
    public function displayListsUsage()
    {
        if ($this->maxLists() == '∞') {
            return trans('messages.unlimited');
        }
    
        return $this->listsUsage().'%';
    }
    
    /**
     * Get campaigns quota.
     *
     * @return number
     */
    public function maxCampaigns()
    {
        $count = $this->getOption('campaign_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }
    
    /**
     * Count customer's campaigns.
     *
     * @return number
     */
    public function campaignsCount()
    {
        return $this->campaigns()->count();
    }
    
    /**
     * Calculate campaign usage.
     *
     * @return number
     */
    public function campaignsUsage()
    {
        if ($this->maxCampaigns() == '∞') {
            return 0;
        }
        if ($this->maxCampaigns() == 0) {
            return 100;
        }
    
        return round((($this->campaignsCount() / $this->maxCampaigns()) * 100), 2);
    }
    
    /**
     * Calculate campaign usage.
     *
     * @return number
     */
    public function displayCampaignsUsage()
    {
        if ($this->maxCampaigns() == '∞') {
            return trans('messages.unlimited');
        }
    
        return $this->campaignsUsage().'%';
    }
    
    /**
     * Get subscriber quota.
     *
     * @return number
     */
    public function maxSubscribers()
    {
        $count = $this->getOption('subscriber_max');
        if ($count == -1) {
            return '∞';
        } else {
            return $count;
        }
    }
    
    /**
     * Count customer's subscribers.
     *
     * @return number
     */
    public function subscribersCount()
    {
        return $this->subscribers()->count();
    }
    
    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function subscribersUsage()
    {
        if ($this->maxSubscribers() == '∞') {
            return 0;
        }
        if ($this->maxSubscribers() == 0 || $this->subscribersCount() > $this->maxSubscribers()) {
            return 100;
        }
    
        return round((($this->subscribersCount() / $this->maxSubscribers()) * 100), 2);
    }
    
    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function displaySubscribersUsage()
    {
        if ($this->maxSubscribers() == '∞') {
            return trans('messages.unlimited');
        }
    
        return $this->subscribersUsage().'%';
    }
    
    /**
     * Get customer's quota.
     *
     * @return string
     */
    public function maxQuota()
    {
        $quota = $this->getOption('sending_quota');
        if ($quota == '-1') {
            return '∞';
        } else {
            return $quota;
        }
    }
    
    /**
     * Check if customer has access to ALL sending servers.
     *
     * @return boolean
     */
    public function allSendingServer()
    {
        $check = $this->getOption('all_sending_servers');
        return ($check == 'yes');
    }
    
    /**
     * Get customer's sending quota.
     *
     * @return string
     */
    public function sendingQuota()
    {
        $time_value = $this->getOption('sending_quota_time');
        $time_unit = $this->getOption('sending_quota_time_unit');
    
        if ($time_value == '-1') {
            $begin = null;
        } else {
            if ($time_unit == 'year') {
                $begin = \Carbon\Carbon::now()->subYear($time_value);
            } elseif ($time_unit == 'month') {
                $begin = \Carbon\Carbon::now()->subMonth($time_value);
            } elseif ($time_unit == 'week') {
                $begin = \Carbon\Carbon::now()->subWeek($time_value);
            } elseif ($time_unit == 'day') {
                $begin = \Carbon\Carbon::now()->subDay($time_value);
            } elseif ($time_unit == 'hour') {
                $begin = \Carbon\Carbon::now()->subHour($time_value);
            } elseif ($time_unit == 'minute') {
                $begin = \Carbon\Carbon::now()->subMinute($time_value);
            }
        }
    
        $query = \Acelle\Model\TrackingLog::leftJoin('campaigns', 'campaigns.id', '=', 'tracking_logs.campaign_id');
        $query = $query->where('campaigns.customer_id', '=', $this->id);
        if (isset($begin)) {
            $query = $query->where('tracking_logs.created_at', '>=', $begin);
        }
    
        $count = $query->count();
    
        return $count;
    }
    
    /**
     * Get customer's sending quota rate.
     *
     * @return string
     */
    public function sendingQuotaUsage()
    {
        if ($this->maxQuota() == '∞') {
            return '0';
        } elseif ($this->maxQuota() == '0' || $this->sendingQuota() >= $this->maxQuota()) {
            return '100';
        }
    
        return round((($this->sendingQuota() / $this->maxQuota()) * 100), 2);
    }
    
    /**
     * Check if customer has used up all quota allocated.
     *
     * @return string
     */
    public function overQuota()
    {
        return !$this->getQuotaTracker()->check();
    }
    
    /**
     * Increment quota usage
     *
     * @return void
     */
    public function countUsage(Carbon $timePoint = null)
    {
        return $this->getQuotaTracker($timePoint)->add();
    }
    
    /**
     * Get customer's sending quota rate.
     *
     * @return string
     */
    public function displaySendingQuotaUsage()
    {
        if ($this->getSendingQuota() == -1) {
            return trans('messages.unlimited');
        }
        // @todo use percentage helper here
        return $this->getSendingQuotaUsagePercentage().'%';
    }
    
    /**
     * Get customer's color scheme.
     *
     * @return string
     */
    public function getColorScheme()
    {
        if (!empty($this->color_scheme)) {
            return $this->color_scheme;
        } else {
            return \Acelle\Model\Setting::get('frontend_scheme');
        }
    }
    
    /**
     * Color array.
     *
     * @return array
     */
    public static function colors($default)
    {
        return [
            ['value' => '', 'text' => trans('messages.system_default')],
            ['value' => 'blue', 'text' => trans('messages.blue')],
            ['value' => 'green', 'text' => trans('messages.green')],
            ['value' => 'brown', 'text' => trans('messages.brown')],
            ['value' => 'pink', 'text' => trans('messages.pink')],
            ['value' => 'grey', 'text' => trans('messages.grey')],
            ['value' => 'white', 'text' => trans('messages.white')],
        ];
    }
    
    /**
     * Disable customer.
     *
     * @return boolean
     */
    public function disable()
    {
        $this->status = 'inactive';
        return $this->save();
    }
    
    /**
     * Enable customer.
     *
     * @return boolean
     */
    public function enable()
    {
        $this->status = 'active';
        return $this->save();
    }
    
    /**
     * Get customer's quota.
     *
     * @return string
     */
    public function getSendingQuota()
    {
        // -1 indicate unlimited
        return $this->getOption('sending_quota');
    }
    
    /**
     * Get customer's sending quota.
     *
     * @return string
     */
    public function getSendingQuotaUsage()
    {
        $tracker = $this->getQuotaTracker();
        return $tracker->getUsage();
    }
    
    /**
     * Get customer's sending quota rate.
     *
     * @return string
     */
    public function getSendingQuotaUsagePercentage()
    {
        // @todo magic number
        if ($this->getSendingQuota() == -1) {
            return '0';
        } elseif ($this->getSendingQuota() == '0' || $this->getSendingQuotaUsage() >= $this->getSendingQuota()) {
            return '100';
        }

        return round(($this->getQuotaTracker()->getUsagePercentage() * 100), 2);
    }
    
    /**
     * Get quota starting time
     *
     * @return array
     */
    public function getQuotaStartingTime() {
        $timeValue = $this->getOption('sending_quota_time');
        $timeUnit = $this->getOption('sending_quota_time_unit');
        
        // @todo: magic number here
        // @todo: why $timeValue == -1?
        // @todo: configuration setting for default Quota Starting Time
        if ($timeValue == '-1') {
            $start = \Carbon\Carbon::parse("1 month ago");
        } else {
            $start = \Carbon\Carbon::parse("{$timeValue} {$timeUnit} ago");
        }
        return $start;
    }

    /**
     * Get quota starting time
     *
     * @return array
     */
    public function getQuotaIntervalString() {
        $timeValue = $this->getOption('sending_quota_time');
        $timeUnit = $this->getOption('sending_quota_time_unit');
        return "{$timeValue} {$timeUnit}";
    }

    /**
     * Initialize the quota tracker
     *
     * @return array
     */
    public function renewQuotaTracker() {
        $this->getQuotaTracker()->getExclusiveLock(function() {
            $start = $this->getQuotaStartingTime();
    
            // recent tracking logs
            // @todo: potential issue here, application fails in case $start is NULL
            $recent = $this->trackingLogs()->where('created_at', '>=', $start);
            
            // existing quota usage
            $stored = null;
 
            // load the tracker from DB (if exists)
            // then merge with the current tracking log data
            if ($this->quota) {
                try {
                    $stored = unserialize($this->quota);
                } catch (\Exception $x) {
                    // @TODO logging here
                    MailLog::warning('Cannot retrieve user quota');
                }
            }
    
            // load the tracker from DB
            if (!is_null($stored) && !empty($stored)) {
                // retrieve the stored quota usage and merge it with actual (newer) usage
                $recent = $recent->where('created_at', '>', \Carbon\Carbon::createFromTimestamp(end($stored)));
                $series = $recent->get()->map(function($trackingLog) {
                    return $trackingLog->created_at->timestamp;
                })->toArray();
                
                $series = array_merge($stored, $series);
            } else {
                $series = $recent->get()->map(function($trackingLog) {
                    return $trackingLog->created_at->timestamp;
                })->toArray();
            }
            
            $this->getQuotaTracker()->reset($series);
        });
    }

    /**
     * Initialize the quota tracker
     *
     * @return array
     */
    public function initQuotaTracker() {
        $this->quotaTracker = new QuotaTrackerFile($this->getSendingQuotaLockFile(), $this->getQuotaIntervalString(), $this->getSendingQuota());
        // @note: in case of multi-process, the following command must be issued manually
        //     $this->renewQuotaTracker();
    }
    
    /**
     * Get sending quota lock file
     *
     * @return string file path
     */
    public function getSendingQuotaLockFile() {
        return storage_path("app/customer/quota/{$this->uid}");
    }

    /**
     * Get customer's QuotaTracker
     *
     * @return array
     */
    public function getQuotaTracker() {
        if(!$this->quotaTracker) {
            $this->initQuotaTracker();
        }

        return $this->quotaTracker;
    }

    /**
     * Store the current quota usage info to DB
     *
     * @return array
     */
    public function saveQuotaUsageInfo() {
        $this->quota = serialize($this->getQuotaTracker()->getSeries());
        $this->save();
    }

    public function quotaDebug() {
        echo "During the last " . $this->getQuotaIntervalString() . ", Count: " . $this->getSendingQuotaUsage() . " / Total: " . $this->getSendingQuota() . "\n";
        echo "Now " . \Carbon\Carbon::now()->toDateTimeString() . "\n";
        foreach($this->getQuotaTracker()->getSeries() as $t) {
            echo \Carbon\Carbon::createFromTimestamp($t)->toDateTimeString() . " <-> " ;
        }
        echo "\n" . env('REDIS_ENABLED') . "\n";
    }
    
    /**
     * Get not auto campaign.
     *
     * @return array
     */
    public function getNormalCampaigns()
    {
        return $this->campaigns()->where('is_auto', '=', false);
    }
    
    /**
     * Get customer timezone
     *
     * @return string
     */
    public function getTimezone() {
        return $this->timezone;
    }
    
    /**
     * Get customer language code
     *
     * @return string
     */
    public function getLanguageCode() {
        return (is_object($this->language) ? $this->language->code : null);
    }
    
    /**
     * Get customer select2 select options
     *
     * @return array
     */
    public static function select2() {
        $data = ['items' => [], 'more' => true];
        
        $query = \Acelle\Model\Customer::getAll()->leftJoin('users', 'users.id', '=', 'customers.user_id');
        if (isset($request->q)) {
            $keyword = $request->q;
            $query = $query->where(function ($q) use ($keyword) {
                $q->orwhere('customers.first_name', 'like', '%'.$keyword.'%')
                    ->orWhere('customers.last_name', 'like', '%'.$keyword.'%')
                    ->orWhere('users.email', 'like', '%'.$keyword.'%');
            });
        }
        foreach ($query->limit(20)->get() as $customer) {
            $data['items'][] = ['id' => $customer->id, 'text' => $customer->displayName()];
        }
        
        return json_encode($data);
    }
}
