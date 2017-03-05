<?php

/**
 * PaymentMethod class.
 *
 * Model class for payment methods
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

class PaymentMethod extends Model
{
    // PaymentMethod status
	const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';
	
	// PaymentMethod type
	const TYPE_CASH = 'cash';
    const TYPE_PAYPAL = 'paypal';
	const TYPE_BRAINTREE_PAYPAL = 'braintree_paypal';
	const TYPE_BRAINTREE_CREDIT_CARD = 'braintree_credit_card';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'options'
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
			'type' => 'required',
        );
		
		if($this->type == PaymentMethod::TYPE_PAYPAL) {
			$rules['options.merchant_id'] = 'required';
		}
		
		if($this->type == PaymentMethod::TYPE_BRAINTREE_PAYPAL || $this->type == PaymentMethod::TYPE_BRAINTREE_CREDIT_CARD) {
			$rules['options.environment'] = 'required';
			$rules['options.merchantId'] = 'required';
			$rules['options.publicKey'] = 'required';
			$rules['options.privateKey'] = 'required';
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
            while (PaymentMethod::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
            
            // Update custom order
            PaymentMethod::getAll()->increment('custom_order', 1);
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
        return PaymentMethod::select('*');
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
        $query = self::select('payment_methods.*');
        
        if ($request->user()->admin->getPermission('payment_method_read') == 'own') {
            $query = $query->where('payment_methods.admin_id', '=', $request->user()->admin->id);
        }
        
        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('payment_methods.name', 'like', '%'.$keyword.'%');
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
        
        if (!empty($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }
    
    /**
     * Disable payment_method.
     *
     * @return boolean
     */
    public function disable()
    {
        $this->status = PaymentMethod::STATUS_INACTIVE;
        return $this->save();
    }
    
    /**
     * Enable payment_method.
     *
     * @return boolean
     */
    public function enable()
    {
        $this->status = PaymentMethod::STATUS_ACTIVE;
        return $this->save();
    }
    
    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        return json_decode($this->options, true);
    }

    /**
     * Get option.
     *
     * @return string
     */
    public function getOption($name)
    {
		$options = $this->getOptions();
        return isset($options[$name]) ? $options[$name] : NULL;
    }
    
    /**
     * Get customer select2 select options
     *
     * @return array
     */
    public static function select2($request) {
        $data = ['items' => [], 'more' => true];
        
        $query = \Acelle\Model\PaymentMethod::getAll()->orderBy('custom_order', 'asc');
        if (isset($request->q)) {
            $keyword = $request->q;
            $query = $query->where(function ($q) use ($keyword) {
                $q->orwhere('payment_methods.name', 'like', '%'.$keyword.'%');
            });
        }
        foreach ($query->limit(20)->get() as $payment_method) {
            $data['items'][] = ['id' => $payment_method->uid, 'text' => $payment_method->name];
        }
        
        return json_encode($data);
    }
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAllActive()
    {
        return PaymentMethod::getAll()
			->where("payment_methods.status", "=", PaymentMethod::STATUS_ACTIVE)
			->orderBy('custom_order', 'asc')->get();
    }
	
	/**
     * Payment methos types select options.
     *
     * @return array
     */
    public static function typeSelectOptions()
    {
        return [
            ['value' => PaymentMethod::TYPE_CASH, 'text' => trans('messages.' . PaymentMethod::TYPE_CASH)],
			['value' => PaymentMethod::TYPE_BRAINTREE_PAYPAL, 'text' => trans('messages.' . PaymentMethod::TYPE_BRAINTREE_PAYPAL)],
			['value' => PaymentMethod::TYPE_BRAINTREE_CREDIT_CARD, 'text' => trans('messages.' . PaymentMethod::TYPE_BRAINTREE_CREDIT_CARD)],
        ];
    }
	
	/**
     * Set Braintree auth info.
     *
     * @return void
     */
    public function setAuth()
    {
        \Braintree_Configuration::environment($this->getOption('environment'));
        \Braintree_Configuration::merchantId($this->getOption('merchantId'));
        \Braintree_Configuration::publicKey($this->getOption('publicKey'));
        \Braintree_Configuration::privateKey($this->getOption('privateKey'));
    }
	
	/**
     * Get payment method by type.
     *
     * @return object
     */
    public static function getByType($type)
    {
        return PaymentMethod::where("payment_methods.type", "=", $type)
			->first();
    }
}
