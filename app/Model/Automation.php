<?php

/**
 * Automation class.
 *
 * Model for automations
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

class Automation extends Model
{
    /**
     * Items per page.
     *
     * @var array
     */
    const ITEMS_PER_PAGE = 25;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mail_list_id', 'segment_id'
    ];
    
    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating automation.
        static::creating(function ($automation) {
            // Create new uid
            $uid = uniqid();
            while (Automation::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $automation->uid = $uid;

            // Update custom order
            Automation::select('custom_order')->increment('custom_order', 1);
            $automation->custom_order = 0;
        });
    }
    
    /**
     * Associations.
     *
     * @var object | collect
     */
    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }
    
    public function segment()
    {
        return $this->belongsTo('Acelle\Model\Segment');
    }
    
    public function autoEvents()
    {
        return $this->hasMany('Acelle\Model\AutoEvent');
    }
    
    /**
     * The validation rules for automation.
     *
     * @var array
     */
    public function rules()
    {
        return [
            'init' => [
                'name' => 'required',
                'mail_list_uid' => 'required',
            ]
        ];
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
     * Fill attributes from array.
     *
     * @var void
     */
    public function fillAttributes($params)
    {
        $this->fill($params);
        
        // Fill mail list with mail_list_uid
        if(!empty($params["mail_list_uid"])) {
            $this->mail_list_id = MailList::findByUid($params["mail_list_uid"])->id;
        }
        
        // Fill segment with segment_uid
        if(!empty($params["segment_uid"])) {
            $this->segment_id = Segment::findByUid($params["segment_uid"])->id;
        }
    }
    
    /**
     * Get the first/init auto event.
     *
     * @var AutoEvent
     */
    public function getInitEvent()
    {
        $auto_event = $this->autoEvents()->where('previous_event_id', '=', NULL)
            ->orderBy('created_at', 'DESC')
            ->first();
        
        if(!is_object($auto_event)) {
            $auto_event = new AutoEvent([
                'event_type' => AutoEvent::TYPE_SPECIFIC_DATE,
            ]);
            $auto_event->automation_id = $this->id;
        }
        
        return $auto_event;
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
        
        return $query->distinct();
    }
}
