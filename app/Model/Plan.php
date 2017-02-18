<?php

/**
 * Plan class.
 *
 * Model class for Plan
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

class Plan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'frequency_amount', 'frequency_unit', 'price', 'color'
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        $rules = array(
            'name' => 'required',
            'frequency_amount' => 'required|min:1',
            'frequency_unit' => 'required',
            'price' => 'required|min:0',
            'color' => 'required',
        );
    
        $options = self::defaultOptions();
        foreach ($options as $type => $option) {
            $rules['options.' . $type] = 'required';
        }
        
        return $rules;
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
    
    public function admin()
    {
        return $this->belongsTo('Acelle\Model\Admin');
    }
    
    public function plansSendingServers()
    {
        return $this->hasMany('Acelle\Model\PlansSendingServer');
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
            while (Plan::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
            
            // Update custom order
            Plan::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
        });
    }
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return Plan::select('*');
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
        $query = self::select('plans.*');
        
        if ($request->user()->admin->getPermission('plan_read') == 'own') {
            $query = $query->where('plans.admin_id', '=', $request->user()->admin->id);
        }
        
        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('plans.name', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;

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
     * Disable plan.
     *
     * @return boolean
     */
    public function disable()
    {
        $this->status = 'inactive';
        return $this->save();
    }
    
    /**
     * Enable plan.
     *
     * @return boolean
     */
    public function enable()
    {
        $this->status = 'active';
        return $this->save();
    }
    
    /**
     * Plan time type.
     *
     * @return array
     */
    public static function frequencySelectOptions()
    {
        return [
            ['value' => 'monthly', 'text' => trans('messages.monthly')],
            ['value' => 'yearly', 'text' => trans('messages.yearly')],
            ['value' => 'daily', 'text' => trans('messages.daily')],
        ];
    }
    
    /**
     * Color array.
     *
     * @return array
     */
    public static function colors($default)
    {
        return [
            ['value' => 'rgb(20,130,160)', 'text' => trans('messages.blue')],
            ['value' => 'rgb(0,140,110)', 'text' => trans('messages.green')],
            ['value' => 'rgb(145,115,25)', 'text' => trans('messages.brown')],
            ['value' => 'rgb(170,80,100)', 'text' => trans('messages.pink')],
            ['value' => '#555', 'text' => trans('messages.grey')],
        ];
    }
    
    /**
     * Frequency time unit options.
     *
     * @return array
     */
    public static function timeUnitOptions()
    {
        return [
            ['value' => 'day', 'text' => trans('messages.day')],
            ['value' => 'week', 'text' => trans('messages.week')],
            ['value' => 'month', 'text' => trans('messages.month')],
            ['value' => 'year', 'text' => trans('messages.year')],
        ];
    }
    
    /**
     * Default options for new plan.
     *
     * @return array
     */
    public static function defaultOptions()
    {
        return [
            'list_max' => '-1',
            'subscriber_max' => '-1',
            'subscriber_per_list_max' => '-1',
            'segment_per_list_max' => '3',
            'campaign_max' => '-1',
            'sending_quota' => '-1',
            'sending_quota_time' => '-1',
            'sending_quota_time_unit' => 'month',
            'max_process' => '1',
            'all_sending_servers' => 'yes',
            'max_size_upload_total' => '500',
            'max_file_size_upload' => '5',
            'unsubscribe_url_required' => 'yes',
            'access_when_offline' => 'no',
            'allow_customer_create_sending_servers' => 'no',
            'sending_servers_max' => '-1',
        ];
    }
    
    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->options)) {
            return self::defaultOptions();
        } else {
            $defaul_options = self::defaultOptions();
            $saved_options = json_decode($this->options, true);
            foreach($defaul_options as $x => $group) {
                if(isset($saved_options[$x])) {
                    $defaul_options[$x] = $saved_options[$x];
                }
            }
            return $defaul_options;
        }
    }

    /**
     * Get option.
     *
     * @return string
     */
    public function getOption($name)
    {
        return $this->getOptions()[$name];
    }
    
    /**
     * Get sending servers ids.
     *
     * @return array
     */
    public function getSendingServerIds()
    {
        $arr = [];
        foreach ($this->sending_servers as $server) {
            $arr[] = $server->uid;
        }

        return $arr;
    }

    /**
     * Update sending servers.
     *
     * @return array
     */
    public function updateSendingServers($servers)
    {
        $this->plansSendingServers()->delete();
        foreach ($servers as $key => $param) {
            if ($param['check']) {
                $server = SendingServer::findByUid($key);
                $row = new PlansSendingServer();
                $row->plan_id = $this->id;
                $row->sending_server_id = $server->id;
                $row->fitness = $param['fitness'];
                $row->save();
            }
        }
    }

    /**
     * Multi process select options.
     *
     * @return array
     */
    public static function multiProcessSelectOptions()
    {
        $options = [['value' => 1, 'text' => trans('messages.one_single_process')]];
        for ($i = 2; $i < 101; ++$i) {
            $options[] = ['value' => $i, 'text' => $i];
        }

        return $options;
    }
    
    /**
     * Display group quota.
     *
     * @return array
     */
    public function displayQuota()
    {
        if ($this->getOption("sending_quota") == -1) {
            return trans('messages.unlimited');
        } elseif ($this->getOption("sending_quota_time") == -1) {
            return $this->getOption("sending_quota");
        } else {
            return $this->getOption("sending_quota") . " " . trans('messages.' . \Acelle\Library\Tool::getPluralPrase('email', $this->getOption("sending_quota"))) . ' / '.$this->getOption("sending_quota_time").' '.trans('messages.'.\Acelle\Library\Tool::getPluralPrase($this->getOption("sending_quota_time_unit"), $this->getOption("sending_quota")));
        }        
    }
    
    /**
     * Display max lists.
     *
     * @return array
     */
    public function displayMaxList()
    {
        if ($this->getOption("list_max") == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption("list_max");
        }        
    }
    
    /**
     * Display max subscribers.
     *
     * @return array
     */
    public function displayMaxSubscriber()
    {
        if ($this->getOption("subscriber_max") == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption("subscriber_max");
        }        
    }
    
    /**
     * Display max campaign.
     *
     * @return array
     */
    public function displayMaxCampaign()
    {
        if ($this->getOption("campaign_max") == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption("campaign_max");
        }        
    }
    
    /**
     * Display max campaign.
     *
     * @return array
     */
    public function displayMaxSizeUploadTotal()
    {
        if ($this->getOption("max_size_upload_total") == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption("max_size_upload_total");
        }        
    }
    
    /**
     * Display max campaign.
     *
     * @return array
     */
    public function displayFileSizeUpload()
    {
        if ($this->getOption("max_file_size_upload") == -1) {
            return trans('messages.unlimited');
        } else {
            return $this->getOption("max_file_size_upload");
        }        
    }
    
    /**
     * Get customer select2 select options
     *
     * @return array
     */
    public static function select2() {
        $data = ['items' => [], 'more' => true];
        
        $query = \Acelle\Model\Plan::getAll();
        if (isset($request->q)) {
            $keyword = $request->q;
            $query = $query->where(function ($q) use ($keyword) {
                $q->orwhere('plans.name', 'like', '%'.$keyword.'%');
            });
        }
        foreach ($query->limit(20)->get() as $plan) {
            $data['items'][] = ['id' => $plan->id, 'text' => $plan->name];
        }
        
        return json_encode($data);
    }
}
