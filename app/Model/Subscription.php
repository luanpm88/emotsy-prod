<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // Subscription status
    const STATUS_ACTIVE = 'active'; // equiv. to 'queue'
	const STATUS_INACTIVE = 'inactive';
	
	// Subscription state
    const TIME_STATUS_FUTURE = 'future'; // equiv. to 'queue'
	const TIME_STATUS_CURRENT = 'current';
	const TIME_STATUS_PAST = 'past';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'start_at',
        'end_at'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id', 'payment_method_id'
    ];
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        $rules = array(
            'plan_uid' => 'required',
            'customer_uid' => 'required'
        );
        
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
    
    /**
     * Associations.
     *
     * @var object | collect
     */
    public function plan()
    {
        return $this->belongsTo('Acelle\Model\Plan');
    }
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }
	public function paymentMethod()
    {
        return $this->belongsTo('Acelle\Model\PaymentMethod');
    }
    
    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();		

        // Create uid when creating list.
        static::creating(function ($subscription) {
            // Create new uid
            $uid = uniqid();
            while (Subscription::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $subscription->uid = $uid;
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
        $query = self::select('subscriptions.*')
			->join('customers', 'customers.id', '=', 'subscriptions.customer_id');
        
        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('customers.first_name', 'like', '%'.$keyword.'%')
						->orwhere('customers.last_name', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
		
		if (!empty($filters)) {
            if (!empty($filters['customer_uid'])) {
                $query = $query->where('customers.uid', '=', $filters['customer_uid']);
            }
			if (!empty($filters['plan_uid'])) {
				$plan = \Acelle\Model\Plan::findByUid($filters['plan_uid']);
                $query = $query->where('subscriptions.plan_id', '=', $plan->id);
			}
			if (!empty($filters['status'])) {
                $query = $query->where('subscriptions.status', '=', $filters['status']);
            }	
        }
		
		// Other filter
        if(!empty($request->customer_id)) {
            $query = $query->where('subscriptions.customer_id', '=', $request->customer_id);
        }
		
		// Other filter
        if(!empty($request->customer_admin_id)) {
            $query = $query->where('customers.admin_id', '=', $request->customer_admin_id);
        }
		
		if (!empty($request->customer_list)) {
			$query = $query->where('subscriptions.end_at', '>=', \Carbon\Carbon::now()->startOfDay());
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

        if(isset($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        } else {
			$query = $query->orderBy('subscriptions.created_at', 'asc');
		}

        return $query;
    }
    
    /**
     * Fill attributes.
     *
     * @return collect
     */
    public function fillAttributes($params)
    {
        $this->plan_id = $this->plan_id ? $this->plan_id : (isset($params['plan_uid']) ? \Acelle\Model\Plan::findByUid($params['plan_uid'])->id : null);
		$this->customer_id = $this->customer_id ? $this->customer_id : (isset($params['customer_uid']) ? \Acelle\Model\Customer::findByUid($params['customer_uid'])->id : null);
		$this->payment_method_id = $this->payment_method_id ? $this->payment_method_id : (isset($params['payment_method_uid']) ? \Acelle\Model\PaymentMethod::findByUid($params['payment_method_uid'])->id : null);
		$this->start_at = $this->start_at ? $this->start_at : $this->customer->getNextScriptionStartAt();
		
        $plan = $this->plan_id ? Plan::find($this->plan_id) : null;
        
        $this->fill($params);
        
        if(isset($this->plan_id)) {
			// Save info from plan
            $this->price = $plan->price;
			$this->plan_name = $plan->name;
			$this->plan_color = $plan->color;
			$this->currency_code = $plan->currency->code;
			$this->currency_format = $plan->currency->format;
			$this->options = json_encode($plan->getOptions());
            
            switch ($plan->frequency_unit) {
                case 'day':
                    $this->end_at = $this->start_at->addDays($plan->frequency_amount);
                    break;
                case 'week':
                    $this->end_at = $this->start_at->addWeeks($plan->frequency_amount);
                    break;
                case 'month':
                    $this->end_at = $this->start_at->addMonths($plan->frequency_amount);
                    break;
                case 'year':
                    $this->end_at = $this->start_at->addYears($plan->frequency_amount);
                    break;
            }
        }
    }
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public function frontendRules()
    {
        $rules = array(
            'plan_uid' => 'required',
        );
        
        return $rules;
    }
	
	/**
     * Disable subscription.
     *
     * @return boolean
     */
    public function disable()
    {
        $this->status = Subscription::STATUS_INACTIVE;
        return $this->save();
    }
    
    /**
     * Enable subscription.
     *
     * @return boolean
     */
    public function enable()
    {
        $this->status = Subscription::STATUS_ACTIVE;
        return $this->save();
    }
	
	/**
     * Check if subscription is free.
     *
     * @return boolean
     */
    public function isFree()
    {
        return $this->price == 0;
    }
	
	/**
     * Count remaining days.
     *
     * @return integer
     */
    public function daysRemainCount()
    {
        $count = $this->end_at->diffInDays(\Carbon\Carbon::now());
		
		return $count;
    }
	
	/**
     * Default options for new plan.
     *
     * @return array
     */
    public static function defaultOptions()
    {
        return [
            'list_max' => '0',
            'subscriber_max' => '0',
            'subscriber_per_list_max' => '0',
            'segment_per_list_max' => '0',
            'campaign_max' => '0',
            'sending_quota' => '0',
            'sending_quota_time' => '0',
            'sending_quota_time_unit' => 'month',
            'max_process' => '0',
            'all_sending_servers' => 'no',
            'max_size_upload_total' => '0',
            'max_file_size_upload' => '0',
            'unsubscribe_url_required' => 'yes',
            'access_when_offline' => 'no',
            'create_sending_servers' => 'no',
            'sending_servers_max' => '0',
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
     * Check if subscription is active.
     *
     * @return array
     */
    public function isActive()
    {
        return $this->status == Subscription::STATUS_ACTIVE;
    }
	
	/**
     * Check if subscription is outdated.
     *
     * @return array
     */
    public function isOld()
    {
        return isset($this->end_at) ? $this->end_at->endOfDay() < \Carbon\Carbon::now() : false;
    }
	
	/**
     * Check if subscription is current used.
     *
     * @return array
     */
    public function beingUsed()
    {
        if($this->start_at->startOfDay() <= \Carbon\Carbon::now() &&
		   !$this->isOld() &&
		   $this->isActive()
		) {
			return true;
		}
		
		return false;
    }
	
	/**
     * Check current state
     *
     * @return array
     */
    public function timeStatus()
    {		
        if ($this->start_at->startOfDay() > \Carbon\Carbon::now()) {
			return Subscription::TIME_STATUS_FUTURE;
		} elseif ($this->isOld()) {
			return Subscription::TIME_STATUS_PAST;
		} else {
			return Subscription::TIME_STATUS_CURRENT;
		}
    }
	
	/**
     * Get subsciption display name
     *
     * @return array
     */
    public function getName() {
		return $this->plan->name;
	}
	
	/**
     * Get id for payment
     *
     * @return array
     */
    public function getID() {
		return $this->uid;
	}
	
	/**
     * Check if subscriptioon is paid
     *
     * @return array
     */
    public function isPaid() {
		return $this->paid;
	}
	
	/**
     * When get status attribute
     *
     * @return array
     */
    public function getStatusAttribute($value) {
		// Auto update status
		if ($this->isOld()) {
			$this->disable();
		}
		return $value;
	}
}
