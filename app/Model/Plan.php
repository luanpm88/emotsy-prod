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
        'name', 'customer_group_id', 'frequency', 'price', 'color'
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        return array(
            'name' => 'required',
            'customer_group_id' => 'required',
            'frequency' => 'required',
            'price' => 'required',
            'color' => 'required',
        );
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
    public function customerGroup()
    {
        return $this->belongsTo('Acelle\Model\CustomerGroup');
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
            while (Plan::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
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
        $query = self::select('plans.*')
                        ->leftJoin('customer_groups', 'customer_groups.id', '=', 'plans.customer_group_id');
        
        if ($request->user()->admin->getPermission('plan_read') == 'own') {
            $query = $query->where('plans.admin_id', '=', $request->user()->admin->id);
        }
        
        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('plans.name', 'like', '%'.$keyword.'%')
                        ->orWhere('customer_groups.name', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            if (!empty($filters['customer_group_id'])) {
                $query = $query->where('plans.customer_group_id', '=', $filters['customer_group_id']);
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
        $query = self::filter($request);

        $query = $query->orderBy($request->sort_order, $request->sort_direction);

        return $query;
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
            ['value' => '#fff', 'text' => trans('messages.white')],
        ];
    }
}
