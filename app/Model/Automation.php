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
    // Automation status
	const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active'; // equiv. to 'queue'
	const STATUS_INACTIVE = 'inactive';
    
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
		
		// When automation saving.
        static::saving(function ($automation) {
            // Update mailist and segment for all campaigns
			$campaigns = $automation->getCampaigns()->get();
			foreach($campaigns as $campaign) {
				$campaign->mail_list_id = $automation->mail_list_id;
				$campaign->segment_id = $automation->segment_id;
				$campaign->save();
			}
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
    
    public function getCampaigns($request=NULL)
    {
        $query = Campaign::select('campaigns.*')
			->leftJoin('auto_campaigns', 'auto_campaigns.campaign_id', '=', 'campaigns.id')
			->leftJoin('auto_events', 'auto_campaigns.auto_event_id', '=', 'auto_events.id')
			->leftJoin('automations', 'auto_events.automation_id', '=', 'automations.id')
            ->where('automations.id', '=', $this->id);
			
		// Keyword
		if(isset($request)) {
			if (!empty(trim($request->keyword))) {
				$query = $query->where('campaigns.name', 'like', '%'.$request->keyword.'%');
			}
		}
			
		$query = $query->orderBy('auto_events.created_at', 'asc');
		
		return $query;
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
        } elseif(!empty($params)) {
			$this->segment_id = null;
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
            ->orderBy('created_at', 'ASC')
            ->first();
        
        if(!is_object($auto_event)) {
            $auto_event = new AutoEvent([
                'event_type' => AutoEvent::TYPE_SPECIFIC_DATETIME,
            ]);
			$auto_event->status = AutoEvent::STATUS_DRAFT;
			
            $auto_event->automation_id = $this->id;
        }
        
        return $auto_event;
    }
    
    /**
     * Get the first/init auto event.
     *
     * @var AutoEvent
     */
    public function getFollowUpEvents($show_inactive=true)
    {
        $events = collect();
        $event = $this->getInitEvent();
        while(is_object($event->nextEvent)) {
			$event = $event->nextEvent;
			if($show_inactive || $event->status != AutoEvent::STATUS_INACTIVE) {				
				$events->push($event);
			}
        }
        
        return $events;
    }
    
    /**
     * Get last auto event.
     *
     * @var AutoEvent
     */
    public function getLastEvent()
    {
        $event = $this->getInitEvent();
        
        while (is_object($event->nextEvent)) {
            $event = $event->nextEvent;
        }
        
        return $event;
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
    
    /**
     * Create new auto event.
     *
     * @return array
     */
    public function createAutoEvent()
    {
        $event = new AutoEvent([
			'event_type' => AutoEvent::TYPE_FOLLOW_UP
		]);
		$event->status = AutoEvent::STATUS_DRAFT;
		
        $event->updateData([
            'delay_type' => 'after',
            'delay_value' => '1',
            'delay_unit' => 'day',
        ]);
        $event->previous_event_id = $this->getLastEvent()->id;
		
		$this->autoEvents()->save($event);
        
        return $event;
    }
    
    /**
     * Delete automation with its events.
     *
     * @return array
     */
    public function doDelete()
    {
        // remove contraints for deleting auto events
        $this->autoEvents()->update([
            'previous_event_id' => NULL
        ]);
        
        // Delete automation
        $this->delete();
    }
    
    /**
     * Automation valid.
     *
     * @return array
     */
    public function isValid()
    {
        // subscribers count > 0
        if($this->subscribers()->count() <= 0) {
            return false;
        }
        
        // check first auto event
        $first_event = $this->getInitEvent();
        if(!isset($first_event->id)) {
            return false;
        }        
        
		// check each auto events is valid
        foreach($this->autoEvents as $auto_event) {
			if(!$auto_event->isValid()) {
				return false;
			}
        }
        
        return true;
    }
    
    /**
     * set automation status to active.
     *
     * @return void
     */
    public function setActive()
    {
        $this->status = Automation::STATUS_ACTIVE;
		
		// set active for all events if they are draft
        foreach($this->autoEvents as $auto_event) {
            if($auto_event->status == AutoEvent::STATUS_DRAFT) {
				$auto_event->setActive();
			}
        }
		
        $this->save();
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
            'type' => 'automation',
            'name' => $name,
            'data' => json_encode($data),
        ]);
	}
	
	/**
     * enable automation.
     *
     * @return void
     */
    public function enable()
    {
        $this->status = Automation::STATUS_ACTIVE;		
        $this->save();
    }
	
	/**
     * disable automation.
     *
     * @return void
     */
    public function disable()
    {
        $this->status = Automation::STATUS_INACTIVE;		
        $this->save();
    }
}
