<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // Automation status
    const STATUS_ACTIVE = 'active'; // equiv. to 'queue'
	const STATUS_INACTIVE = 'inactive';
    
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
        'plan_id', 'customer_id', 'start_at', 'quantity', 'note'
    ];
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        $rules = array(
            'plan_id' => 'required',
            'customer_id' => 'required'
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
        $query = self::select('subscriptions.*');
        
        //// Keyword
        //if (!empty(trim($request->keyword))) {
        //    foreach (explode(' ', trim($request->keyword)) as $keyword) {
        //        $query = $query->where(function ($q) use ($keyword) {
        //            $q->orwhere('plans.name', 'like', '%'.$keyword.'%');
        //        });
        //    }
        //}

        // filters
        $filters = $request->filters;

        return $query;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request, $customer_id=null)
    {
        $query = self::filter($request);
        
        // Filer by customer
        if($customer_id) {
            $query = $query->where('subscriptions.customer_id', '=', $customer_id);
        }

        if(isset($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        } else {
			$query = $query->orderBy('created_at', 'desc');
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
        $this->quantity = empty($params['quantity']) ? 1 : $params['quantity'];
        $this->plan_id = $this->plan_id ? $this->plan_id : (isset($params['plan_id']) ? $params['plan_id'] : null);
        
        $plan = $this->plan_id ? Plan::find($this->plan_id) : null;
        
        $this->fill($params);
        
        if(isset($this->plan_id)) {
            $this->price = $this->quantity * $plan->price;
            
            if(empty($params["start_at"])) {
                $this->start_at = \Carbon\Carbon::now();
            }
            
            switch ($plan->frequency_unit) {
                case 'day':
                    $this->end_at = $this->start_at->addDays($plan->frequency_amount * $this->quantity);
                    break;
                case 'week':
                    $this->end_at = $this->start_at->addWeeks($plan->frequency_amount * $this->quantity);
                    break;
                case 'month':
                    $this->end_at = $this->start_at->addMonths($plan->frequency_amount * $this->quantity);
                    break;
                case 'year':
                    $this->end_at = $this->start_at->addYears($plan->frequency_amount * $this->quantity);
                    break;
            }
            
            $this->options = $plan->options;
            $this->price = $plan->price * $this->quantity;
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
            'plan_id' => 'required',
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
}
