<?php

/**
 * AutoEvent class.
 *
 * Model for auto events
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
use Carbon\Carbon;
use Acelle\Library\Log as MailLog;

class AutoEvent extends Model
{
	// event status
	const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
	const STATUS_INACTIVE = 'inactive';
	
    // event type
    const TYPE_SPECIFIC_DATE = 'specific-date';
	const TYPE_MONTHLY_RECURRING = 'monthly-recurring';
	const TYPE_WEEKLY_RECURRING = 'weekly-recurring';
	const TYPE_LIST_SUBSCRIPTION = 'list-subscription';
	const TYPE_LIST_UNSUBSCRIPTION = 'list-unsubscription';
	const TYPE_SUBSCRIBER_EVENT = 'subscriber-event';
	const TYPE_CUSTOM_CRITERIA = 'custom-criteria';
	const TYPE_API_CALL = 'api-call';
	const TYPE_FOLLOW_UP = 'follow-up';
	const TYPE_FOLLOW_UP_OPENED = 'follow-up-opened';
	const TYPE_FOLLOW_UP_CLICKED = 'follow-up-clicked';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_type'
    ];
	
	/**
     * Associations.
     *
     * @var object | collect
     */
    public function automation()
    {
        return $this->belongsTo('Acelle\Model\Automation');
    }
	
	public function campaigns()
    {
        return $this->belongsToMany('Acelle\Model\AutomatedCampaign', 'auto_campaigns', 'auto_event_id', 'campaign_id');
    }
	
	public function originCampaigns()
    {
        return $this->belongsToMany('Acelle\Model\Campaign', 'auto_campaigns', 'auto_event_id', 'campaign_id');
    }

    /**
     * Get campaign tracking logs.
     *
     * @return mixed
     */
    public function autoTriggers()
    {
        return $this->hasMany('Acelle\Model\AutoTrigger');
    }
	
	/**
     * Set uid for resource.
     *
     * @var void
     */
    public function createUid()
    {
        $uid = uniqid();
		while (AutoEvent::where('uid', '=', $uid)->count() > 0) {
			$uid = uniqid();
		}
		$this->uid = $uid;
    }
	
	/**
     * Create custom order for the resource.
     *
     * @var void
     */
    public function createCustomOrder()
    {
        AutoEvent::select('custom_order')->increment('custom_order', 1);
        $this->custom_order = 0;
    }
	
	/**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating automation.
        static::creating(function ($event) {
            // Create new uid
            $event->createUid();

            // Update custom order
            $event->createCustomOrder();
        });
		
		// Create first campaign when auto event created.
        static::created(function ($event) {
			// Create first campaign
			$event->createFirstCampaign();
        });
		
		// when auto event is deleted
        static::deleting(function ($event) {
			// Update auto campaign series
			$next_event = $event->nextEvent;
			if(is_object($next_event)) {
				$next_event->previous_event_id = $event->previous_event_id;
				$next_event->save();
			}
			
			// Delete all campaigns
			$event->campaigns()->delete();
        });
    }
	
	/**
     * Create first campaign for the auto event.
     *
     * @var void
     */
    public function createFirstCampaign()
    {
        if(!$this->campaigns()->count()) {
			$this->addCampaign();			
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
     * The validation rules for automation trigger.
     *
     * @var array
     */
    public function rules($request=NULL)
    {
        $rules = [];
        switch ($this->event_type) {
			case AutoEvent::TYPE_SPECIFIC_DATE:
				$rules = [
					'specific_day' => 'required',
					'specific_time' => 'required',
				];
				break;
			case AutoEvent::TYPE_WEEKLY_RECURRING:
				$rules = [
					'weekly_recurring_weekdays' => 'required',
					'weekly_recurring_weeks' => 'required',
					'weekly_recurring_months' => 'required',
					'weekly_recurring_time' => 'required',
				];
				break;
			case AutoEvent::TYPE_MONTHLY_RECURRING:
				$rules = [
					'monthly_recurring_days' => 'required',
					'monthly_recurring_months' => 'required',
					'monthly_recurring_time' => 'required',
				];
				break;
			case AutoEvent::TYPE_SUBSCRIBER_EVENT:
				$rules = [
					'subscriber_event' => 'required',
					'delay_type' => 'required',
					'delay_value' => 'required',
					'delay_unit' => 'required',
				];
				break;
			case AutoEvent::TYPE_CUSTOM_CRITERIA:
				if (isset($request->custom_criteria)) {
					foreach ($request->custom_criteria as $key => $param) {
						$rules['custom_criteria.'.$key.'.field_uid'] = 'required';
						$rules['custom_criteria.'.$key.'.operator'] = 'required';
						$rules['custom_criteria.'.$key.'.value'] = 'required';
					}
				} else {
					$rules['custom_criteria_empty'] = 'required';
				}
				break;
        }
        
        return $rules;
    }
    
    /**
     * Tigger type name select option.
     *
     * @var array
     */
    public static function typeNameSelectOptions()
    {
        return [
            ['value' => self::TYPE_SPECIFIC_DATE, 'text' => trans('messages.Immediately_or_on_a_specific_date')],
            ['value' => self::TYPE_WEEKLY_RECURRING, 'text' => trans('messages.weekly_recurring')],
			['value' => self::TYPE_MONTHLY_RECURRING, 'text' => trans('messages.monthly_recurring')],
            ['value' => self::TYPE_LIST_SUBSCRIPTION, 'text' => trans('messages.Event_based_list_subscription')],
            ['value' => self::TYPE_LIST_UNSUBSCRIPTION, 'text' => trans('messages.Event_based_list_unsubscription')],
            ['value' => self::TYPE_SUBSCRIBER_EVENT, 'text' => trans('messages.Event_based_custom_list_subscriber_event')],
            ['value' => self::TYPE_CUSTOM_CRITERIA, 'text' => trans('messages.Custom_criteria')],
            ['value' => self::TYPE_API_CALL, 'text' => trans('messages.API_call')],
        ];
    }	
	
    /**
     * Fill attributes from array.
     *
     * @var void
     */
    public function fillAttributes($params)
    {
        $this->fill($params);
		
		$this->data = "[]";
		
		// set delay time
		switch ($this->event_type) {
			case self::TYPE_LIST_SUBSCRIPTION:
			case self::TYPE_LIST_UNSUBSCRIPTION:
			case self::TYPE_SUBSCRIBER_EVENT:
			case self::TYPE_CUSTOM_CRITERIA:
				// Delay time
				$this->updateData([
					'delay_type' => isset($params["delay_type"]) ? $params["delay_type"] : 'before',
					'delay_value' => isset($params["delay_value"]) ? $params["delay_value"] : '0',
					'delay_unit' => isset($params["delay_unit"]) ? $params["delay_unit"] : 'day',
				]);
				break;
		}
		
		// fill other options
		switch ($this->event_type) {
			case self::TYPE_SPECIFIC_DATE:
				// send at specific date
				if(!empty($params["specific_day"]) && !empty($params["specific_time"])) {
					$send_at = \Acelle\Library\Tool::systemTimeFromString($params["specific_day"].' '.$params["specific_time"], \Auth::user()->timezone);
					$this->updateData([
						'date' => $send_at->toAtomString()
					]);
				}
				break;
			case self::TYPE_WEEKLY_RECURRING:				
				if(!empty($params["weekly_recurring_weekdays"])) {
					$this->updateData([
						'weekdays' => $params["weekly_recurring_weekdays"]
					]);
				}
				if(!empty($params["weekly_recurring_weeks"])) {
					$this->updateData([
						'weeks' => $params["weekly_recurring_weeks"]
					]);
				}
				if(!empty($params["weekly_recurring_months"])) {
					$this->updateData([
						'months' => $params["weekly_recurring_months"]
					]);
				}
				if(!empty($params["weekly_recurring_time"])) {
					$send_at = \Acelle\Library\Tool::systemTimeFromString('2000-01-01 '.$params["weekly_recurring_time"], \Auth::user()->timezone);
					$this->updateData([
						'time' => $send_at->toAtomString()
					]);
				}
				break;
			case self::TYPE_MONTHLY_RECURRING:
				if(!empty($params["monthly_recurring_days"])) {
					$this->updateData([
						'days' => $params["monthly_recurring_days"]
					]);
				}
				if(!empty($params["monthly_recurring_months"])) {
					$this->updateData([
						'months' => $params["monthly_recurring_months"]
					]);
				}
				if(!empty($params["monthly_recurring_time"])) {
					$send_at = \Acelle\Library\Tool::systemTimeFromString('2000-01-01 '.$params["monthly_recurring_time"], \Auth::user()->timezone);
					$this->updateData([
						'time' => $send_at->toAtomString()
					]);
				}
				break;
			case self::TYPE_SUBSCRIBER_EVENT:
				$this->updateData([
					'event' => isset($params["subscriber_event"]) ? $params["subscriber_event"] : ''
				]);
				break;
			case self::TYPE_CUSTOM_CRITERIA:
				if(!empty($params["custom_criteria"])) {
					$this->updateData([
						'criteria' => $params["custom_criteria"]
					]);
				}
			case self::TYPE_FOLLOW_UP:
			case self::TYPE_FOLLOW_UP_OPENED:
			case self::TYPE_FOLLOW_UP_CLICKED:
				if(!empty($params["delay_value"]) && !empty($params["delay_unit"])) {
					$this->updateData([
						'delay_type' => 'after',
						'delay_value' => $params["delay_value"],
						'delay_unit' => $params["delay_unit"]
					]);
				}
				break;
		}
	}
	
	/**
     * Get data.
     *
     * @return array
     */
    public function getData()
    {
		return ($this->data) ? json_decode($this->data, true) : [];
    }
	
	/**
     * Get data value.
     *
     * @return array
     */
    public function getDataValue(string $name)
    {
		$data = $this->getData();
		return isset($data[$name]) ? $data[$name] : NULL;
    }
	
	/**
     * Update data json.
     *
     * @return void
     */
    public function updateData(array $data)
    {
        $json = $this->getData();
        $this->data = json_encode(array_merge($json, $data));
    }
	
	/**
     * Get operators.
     *
     * @return options
     */
    public static function operators()
    {
        return [
            ['text' => trans('messages.equal'), 'value' => 'equal'],
            ['text' => trans('messages.not_equal'), 'value' => 'not_equal'],
            ['text' => trans('messages.contains'), 'value' => 'contains'],
            ['text' => trans('messages.not_contains'), 'value' => 'not_contains'],
            ['text' => trans('messages.starts'), 'value' => 'starts'],
            ['text' => trans('messages.ends'), 'value' => 'ends'],
            ['text' => trans('messages.not_starts'), 'value' => 'not_starts'],
            ['text' => trans('messages.not_ends'), 'value' => 'not_ends'],
            ['text' => trans('messages.greater'), 'value' => 'greater'],
            ['text' => trans('messages.less'), 'value' => 'less'],
            ['text' => trans('messages.blank'), 'value' => 'blank'],
            ['text' => trans('messages.not_blank'), 'value' => 'not_blank'],
        ];
    }
	
	/**
     * Display message about the auto event.
     *
     * @return array
     */
    public function displayMessage()
    {
		if(empty($this->data)) {
			return trans('messages.event_not_set_yet');
		}
		
		$msg = '';
		switch ($this->event_type) {
			case self::TYPE_SPECIFIC_DATE:
				$msg = trans('messages.on_the_specific_date', [
					'date' => \Acelle\Library\Tool::dayStringFromTimestamp(\Acelle\Library\Tool::dateTimeFromString($this->getDataValue('date'))),
					'time' => \Acelle\Library\Tool::timeStringFromTimestamp(\Acelle\Library\Tool::dateTimeFromString($this->getDataValue('date')))
				]);
				break;
			case self::TYPE_WEEKLY_RECURRING:				
				$msg = trans('messages.weekly_recurring_description', [
					'weekdays' => implode(', ', \Acelle\Library\Tool::numberArrayToWeekdaysArray($this->getDataValue('weekdays'))),
					'weeks' => implode(', ', \Acelle\Library\Tool::numberArrayToWeeksArray($this->getDataValue('weeks'))),
					'months' => implode(', ', \Acelle\Library\Tool::numberArrayToMonthsArray($this->getDataValue('months'))),
					'time' => \Acelle\Library\Tool::timeStringFromTimestamp(\Acelle\Library\Tool::dateTimeFromString($this->getDataValue('time'))),
				]);
				break;
			case self::TYPE_MONTHLY_RECURRING:
				$msg = trans('messages.monthly_recurring_description', [
					'days' => implode(', ', $this->getDataValue('days')),
					'months' => implode(', ', \Acelle\Library\Tool::numberArrayToMonthsArray($this->getDataValue('months'))),
					'time' => \Acelle\Library\Tool::timeStringFromTimestamp(\Acelle\Library\Tool::dateTimeFromString($this->getDataValue('time'))),
				]);
				break;
			case self::TYPE_LIST_SUBSCRIPTION:
				$msg = trans('messages.event_type_list_subscription_description', [
					'list_name' => $this->automation->mailList->name
				]);
				break;
			case self::TYPE_LIST_UNSUBSCRIPTION:
				$msg = trans('messages.event_type_list_unsubscription_description', [
					'list_name' => $this->automation->mailList->name
				]);
				break;
			case self::TYPE_SUBSCRIBER_EVENT:
				$event = $this->getDataValue('event');
				
				// check if event with field uid
				$parts = explode(":", $event);
				if(count($parts) == 2 && $parts[0] == 'field_uid') {
					$field = \Acelle\Model\Field::findByUid($parts[1]);
					$text = is_object($field) ? strtolower(trans('messages.subscriber_s_field', ['name' => $field->label])) : '';
				} else {
					$text = strtolower(trans('messages.subscriber_s_field', ['name' => str_replace('_', ' ', $event)]));
				}
				$msg = trans('messages.event_type_subscriber_event_description', [
					'event' => $text
				]);
				break;
			case self::TYPE_CUSTOM_CRITERIA:
				$strings = [];
				foreach($this->getDataValue('criteria') as $criteria) {
					$field = Field::findByUid($criteria["field_uid"]);
					$strings[] = trans('messages.event_type_custom_criteria_description', [
						'field' => $field->label,
						'operator' => trans('messages.' . $criteria["operator"]),
						'value' => $criteria["value"],
					]);
				}
				$msg = trans('messages.event_type_custom_criterias_description', [
					'conditions' => implode(' or ', $strings)
				]);
				break;
			case self::TYPE_FOLLOW_UP:
			case self::TYPE_FOLLOW_UP_OPENED:
			case self::TYPE_FOLLOW_UP_CLICKED:
				$msg = trans('messages.follow_up_description', [
					'value' => $this->getDataValue('delay_value'),
					'unit' => \Acelle\Library\Tool::getPluralPrase($this->getDataValue('delay_unit'), $this->getDataValue('delay_value')),
					'event' => trans('messages.follow_up_event_' . $this->event_type)
				]);
				break;
			case self::TYPE_API_CALL:				
				$msg = trans('messages.event_type_api_description');
				break;
		}
		
		return $msg;
    }
	
	/**
     * Time unit options.
     *
     * @return array
     */
    public static function timeUnitOptions()
    {
        return [
			['value' => 'hour', 'text' => trans('messages.hour_s')],
			['value' => 'day', 'text' => trans('messages.day_s')],            
            ['value' => 'week', 'text' => trans('messages.week_s')],
            ['value' => 'month', 'text' => trans('messages.month_s')],			
            ['value' => 'year', 'text' => trans('messages.year_s')],
        ];
    }
	
	/**
     * Delay type options.
     *
     * @return array
     */
    public static function delayTypeOptions()
    {
        return [
			['value' => 'before', 'text' => trans('messages.before')],
			['value' => 'after', 'text' => trans('messages.after')],
        ];
    }
	
	/**
     * Email events.
     *
     * @return array
     */
    public static function emailEventOptions()
    {
        return [
			['value' => self::TYPE_FOLLOW_UP, 'text' => strtolower(trans('messages.sent'))],
			['value' => self::TYPE_FOLLOW_UP_OPENED, 'text' => strtolower(trans('messages.opened'))],            
            ['value' => self::TYPE_FOLLOW_UP_CLICKED, 'text' => strtolower(trans('messages.clicked'))],
        ];
    }
	
	/**
     * Add campaign.
     *
     * @return array
     */
    public function addCampaign()
    {
        $campaign = new Campaign([
			'name' => 'Untitled automation email'
		]);
		$campaign->mail_list_id = $this->automation->mail_list_id;
		$campaign->segment_id = $this->automation->segment_id;
		$campaign->user_id = $this->automation->user_id;
		$campaign->is_auto = true;
		
		$this->campaigns()->save($campaign);
		
		return $campaign;
    }
	
	/**
     * Next event.
     *
     * @return object
     */
    public function nextEvent()
    {
        return $this->hasOne('Acelle\Model\AutoEvent', 'previous_event_id');
    }
	
	/**
     * Previous event.
     *
     * @return object
     */
    public function previousEvent()
    {
        return $this->belongsTo('Acelle\Model\AutoEvent', 'previous_event_id');
    }

    /**
     * Check if the critera are met, then trigger the event
     *
     * @return NULL
     */
    public function check()
    {
        $method = $this->camelize('check-for-' . $this->event_type);
        $this->$method();
    }
    
    /**
     * Check if the critera are met, then trigger the event
     *
     * @todo move to Helper instead
     */
    public function camelize($str)
    {
        return lcfirst(
          implode('', array_map(
              'ucfirst', array_map(
                  'strtolower', explode(
                  '-', $str))))
        );
    }
    
    /**
     * Check for specific-datetime events
     *
     * @return NULL
     */
    public function checkForSpecificDate()
    {
        $now = Carbon::now();
        $event_date = Carbon::parse($this->getData()['date']);
        $checked = $now->gte($event_date);
        
        if ($checked) {
            MailLog::info('Trigger sending automation `' . $this->automation->name . '`, event ID: ' . $this->id );
            $this->fire();
        }
    }
    
    /**
     * Actually send automation email
     *
     * @return NULL
     */
    public function fire($subscribers = [])
    {
        // log a trigger record
        $trigger = new AutoTrigger(['start_at' => Carbon::now()]);
        $this->autoTriggers()->save($trigger);
        
        // schedule the corresponding job
        foreach($this->campaigns as $campaign) {
            $campaign->queue($trigger);
        };
    }
	
	/**
     * set event status to active.
     *
     * @return void
     */
    public function setActive()
    {
        $this->status = AutoEvent::STATUS_ACTIVE;		
        $this->save();
    }
	
	/**
     * enable event.
     *
     * @return void
     */
    public function enable()
    {
        $this->status = AutoEvent::STATUS_ACTIVE;		
        $this->save();
    }
	
	/**
     * disable event.
     *
     * @return void
     */
    public function disable()
    {
        $this->status = AutoEvent::STATUS_INACTIVE;		
        $this->save();
    }
	
	/**
     * disable event.
     *
     * @return boolean
     */
    public function isValid()
    {
		// return true if it is inactive
		if($this->status == AutoEvent::STATUS_INACTIVE) {
			return true;
		}
		
        // check auto events emails empty
		if($this->campaigns()->count() <= 0) {
			return false;
		}
		
		// check if email is not design
		foreach($this->campaigns as $campaign) {
			if(!$campaign->autoCampaignDesigned()) {
				return false;
			}
		}
		
		return true;
    }
}
