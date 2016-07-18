<?php

namespace Mailixa\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'first_name', 'last_name', 'email', 'timezone', 'language_id', 'frontend_scheme', 'backend_scheme'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'user_group_id' => 'required',
        'email' => 'required|email',
        'first_name' => 'required',
        'last_name' => 'required',
        'timezone' => 'required',
        'language_id' => 'required',
        'password' => 'confirmed|min:5',
    );
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules() {
        return array(
            'email' => "required|email|unique:users,email," . $this->id . ",id",
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
            'password' => 'confirmed|min:5',
        );
    }
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public function newRules() {
        return array(
            'user_group_id' => 'required',
            'email' => "required|email|unique:users,email," . $this->id . ",id",
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
            'password' => 'required|confirmed|min:5',
        );
    }
    
    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return User::where("uid", "=", $uid)->first();
    }
    
    /**
     * Associations.
     *
     * @var object | collect
     */ 
    public function contact()
    {
        return $this->belongsTo('Mailixa\Model\Contact');
    }
    
    public function user()
    {
        return $this->hasOne('Mailixa\Model\User');
    }
    
    public function userGroup()
    {
        return $this->belongsTo('Mailixa\Model\UserGroup');
    }
    
    public function lists()
    {
        return $this->hasMany('Mailixa\Model\MailList')->orderBy("created_at", "desc");
    }
    
    public function language()
    {
        return $this->belongsTo('Mailixa\Model\Language');
    }
    
    public function campaigns()
    {
        return $this->hasMany('Mailixa\Model\Campaign')->orderBy("created_at", "desc");
    }
    
    public function sentCampaigns()
    {
        return $this->hasMany('Mailixa\Model\Campaign')->where("status", "=" , "sent")->orderBy("created_at", "desc");
    }
    
    public function subscribers()
    {
        return $this->hasMany('Mailixa\Model\Subscriber');
    }
    
    public function logs()
    {
        return $this->hasMany('Mailixa\Model\Log')->orderBy("created_at", "desc");
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
            while(User::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
            
            // Add api token
            $item->api_token = str_random(60);
        });
    }
    
    /**
     * Display user name: first_name last_name.
     *
     * @var string
     */
    public function displayName()
    {
        return $this->first_name . " " . $this->last_name;
    }
    
    /**
     * Upload and resize avatar.
     *
     * @var void
     */
    public function uploadImage($file) {
        $path = 'upload/user/image/';
        $upload_path = storage_path($path);
        $filename = "user-" . $this->id . "." . $file->getClientOriginalExtension();
        
        // save to server
        $file->move($upload_path, $filename);
        
        // create thumbnails
        $img = \Image::make($upload_path . $filename);
        $img->fit(120, 120)->save($upload_path . $filename . '.thumb.jpg');
  
        return $path . $filename;
    }
    
    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function imagePath() {
        if(!empty($this->image) && !empty($this->id)) {
            return storage_path($this->image).".thumb.jpg";
        } else {
            return "";
        }
    }
    
    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function removeImage() {
        if(!empty($this->image) && !empty($this->id)) {
            $path = storage_path($this->image);
            if(is_file($path)) {
                unlink($path);
            }
            if(is_file($path.".thumb.jpg")) {
                unlink($path.".thumb.jpg");
            }
        }
    }
    
    /**
     * Get authenticate from file.
     *
     * @return string
     */
    public static function getAuthenticateFromFile()
    {
        $path = base_path('.authenticate');
        
        if (file_exists($path)) {
            $content = \File::get($path);            
            $lines = explode("\n", $content);
            if (count($lines) > 1) {                
                $demo = session()->get('demo');
                if (!isset($demo) || $demo == "admin") {
                    return ['email' => $lines[0], 'password' => $lines[1]];
                } else {
                    return ['email' => $lines[0], 'password' => $lines[1]];
                }
            }
        }
        
        return ['email' => '', 'password' => ''];
    }
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return MailList::select("*");
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
        $user = $request->user();
        $query = User::select("users.*")
                        ->leftJoin('user_groups', 'user_groups.id', '=', 'users.user_group_id');;
        
        if ($request->user()->getOption("backend", "user_read") == 'own') {
            $query = $query->where("users.user_id", "=", $request->user()->id);
        }
        
        // Keyword
        if(!empty(trim($request->keyword))) {
            foreach(explode(" ",trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('users.first_name', 'like', '%' . $keyword . '%')
                        ->orWhere('users.email', 'like', '%' . $keyword . '%')
                        ->orWhere('user_groups.name', 'like', '%' . $keyword . '%')
                        ->orWhere('users.last_name', 'like', '%' . $keyword . '%');
                });
            }                
        }
        
        // filters
        $filters = $request->filters;
        if(!empty($filters)) {
            if(!empty($filters["user_group_id"])) {
                $query = $query->where("users.user_group_id", "=", $filters["user_group_id"]);     
            }
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
        $query = User::filter($request);
        
        $query = $query->orderBy($request->sort_order, $request->sort_direction);
        
        return $query;
    }
    
    /**
     * Subscribers count by time.
     *
     * @return number
     */
    public static function subscribersCountByTime($begin, $end, $user_id = null, $list_id = null)
    {
        $query = \Mailixa\Model\Subscriber::leftJoin('mail_lists', 'mail_lists.id', '=', 'subscribers.mail_list_id')
                                ->leftJoin('users', 'users.id', '=', 'mail_lists.user_id');
                                
        if (isset($list_id)) {
            $query = $query->where('subscribers.mail_list_id', '=', $list_id);
        }
        if (isset($user_id)) {
            $query = $query->where('users.id', '=', $user_id);
        }
        
        $query = $query->where('subscribers.created_at', '>=', $begin)
                        ->where('subscribers.created_at', '<=', $end);
                        
        return $query->count();
    }
    
    /**
     * Get user setting.
     *
     * @return string
     */
    public function getOption($cat, $name)
    {
        return $this->userGroup->getOption($cat, $name);
    }
    
    /**
     * Get max list quota.
     *
     * @return number
     */
    public function maxLists()
    {
        $count = $this->getOption("frontend", "list_max");
        if ($count == -1) {
            return "∞";
        } else {
            return $count;
        }
    }
    
    /**
     * Count user lists.
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
        if ($this->maxLists() == "∞") {
            return 0;
        }
        if ($this->maxLists() == 0) {
            return 100;
        }
        return round((($this->listsCount()/$this->maxLists())*100), 2);
    }
    
    /**
     * Display calculate list usage.
     *
     * @return number
     */
    public function displayListsUsage()
    {
        if ($this->maxLists() == "∞") {
            return trans('messages.unlimited');
        }

        return $this->listsUsage() . "%";
    }
    
    /**
     * Get campaigns quota.
     *
     * @return number
     */
    public function maxCampaigns()
    {
        $count = $this->getOption("frontend", "campaign_max");
        if ($count == -1) {
            return "∞";
        } else {
            return $count;
        }
    }
    
    /**
     * Count user's campaigns.
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
        if ($this->maxCampaigns() == "∞") {
            return 0;
        }
        if ($this->maxCampaigns() == 0) {
            return 100;
        }
        return round((($this->campaignsCount()/$this->maxCampaigns())*100), 2);
    }
    
    /**
     * Calculate campaign usage.
     *
     * @return number
     */
    public function displayCampaignsUsage()
    {
        if ($this->maxCampaigns() == "∞") {
            return trans('messages.unlimited');
        }
        return $this->campaignsUsage() . "%";
    }
    
    /**
     * Get subscriber quota.
     *
     * @return number
     */
    public function maxSubscribers()
    {
        $count = $this->getOption("frontend", "subscriber_max");
        if ($count == -1) {
            return "∞";
        } else {
            return $count;
        }
    }
    
    /**
     * Count user's subscribers.
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
        if ($this->maxSubscribers() == "∞") {
            return 0;
        }
        if ($this->maxSubscribers() == 0 || $this->subscribersCount() > $this->maxSubscribers()) {
            return 100;
        }
        return round((($this->subscribersCount()/$this->maxSubscribers())*100), 2);
    }
    
    /**
     * Calculate subscibers usage.
     *
     * @return number
     */
    public function displaySubscribersUsage()
    {
        if ($this->maxSubscribers() == "∞") {
            return trans('messages.unlimited');
        }

        return $this->subscribersUsage() . "%";
    }
    
    /**
     * Get user's quota.
     *
     * @return string
     */
    public function maxQuota()
    {
        $quota = $this->getOption("frontend", "sending_quota");
        if ($quota == "-1") {
            return "∞";
        } else {
            return $quota;
        }
    }
    
    /**
     * Get user's sending quota.
     *
     * @return string
     */
    public function sendingQuota()
    {
        $time_value = $this->getOption("frontend", "sending_quota_time");
        $time_unit = $this->getOption("frontend", "sending_quota_time_unit");
        
        if ($time_value == "-1") {
            $begin = null;
        } else {
            if ($time_unit == 'year') {
                $begin = \Carbon\Carbon::now()->subYear($time_value-1)->startOfYear();
            } elseif ($time_unit == 'month') {
                $begin = \Carbon\Carbon::now()->subMonth($time_value-1)->startOfMonth();
            } elseif ($time_unit == 'week') {
                $begin = \Carbon\Carbon::now()->subWeek($time_value-1)->startOfWeek();
            } elseif ($time_unit == 'day') {
                $begin = \Carbon\Carbon::now()->subDay($time_value-1)->startOfDay();
            } elseif ($time_unit == 'hour') {
                $begin = \Carbon\Carbon::now()->subHour($time_value);
            } elseif ($time_unit == 'minute') {
                $begin = \Carbon\Carbon::now()->subMinute($time_value);
            }
        }
        
        $query = \Mailixa\Model\TrackingLog::leftJoin('campaigns', 'campaigns.id', '=', 'tracking_logs.campaign_id');
        $query = $query->where("campaigns.user_id", "=", $this->id);
        if (isset($begin)) {
            $query = $query->where("tracking_logs.created_at", ">=", $begin);
        }
        
        $count = $query->count();
        return $count;
    }
    
    /**
     * Get user's sending quota rate.
     *
     * @return string
     */
    public function sendingQuotaUsage()
    {
        if ($this->maxQuota() == "∞") {
            return "0";
        } elseif ($this->maxQuota() == "0" || $this->sendingQuota() >= $this->maxQuota()) {
            return "100";
        }

        return round((($this->sendingQuota()/$this->maxQuota())*100), 2);
    }

    /**
     * Check if user has used up all quota allocated
     *
     * @return string
     */
    public function overQuota()
    {
        return (int)$this->sendingQuotaUsage() >= 100;
    }
    
    /**
     * Get user's sending quota rate.
     *
     * @return string
     */
    public function displaySendingQuotaUsage()
    {
        if ($this->maxQuota() == "∞") {
            return trans('messages.unlimited');
        }
        
        return $this->sendingQuotaUsage() . "%";
    }
    
    /**
     * Get user's frontend scheme.
     *
     * @return string
     */
    public function getFrontendScheme()
    {
        if (!empty($this->frontend_scheme)) {
            return $this->frontend_scheme;
        } else {
            return \Mailixa\Model\Setting::get("frontend_scheme");
        }
    }
    
    /**
     * Get user's frontend scheme.
     *
     * @return string
     */
    public function getBackendScheme()
    {
        if (!empty($this->backend_scheme)) {
            return $this->backend_scheme;
        } else {
            return \Mailixa\Model\Setting::get("backend_scheme");
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
            ['value' => 'white', 'text' => trans('messages.white')]
        ];
    }
}
