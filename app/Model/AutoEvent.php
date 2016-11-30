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

class AutoEvent extends Model
{
    // event type
    const TYPE_SPECIFIC_DATE = 'specific-date';
	const TYPE_MONTHLY_RECURRING = 'monthly-recurring';
	const TYPE_WEEKLY_RECURRING = 'weekly-recurring';
	const TYPE_LIST_SUBSCRIPTION = 'list-subscription';
	const TYPE_LIST_UNSUBSCRIPTION = 'list-unsubscription';
	const TYPE_SUBSCRIBER_EVENT = 'subscriber-event';
	const TYPE_CUSTOM_CRITERIA = 'custom-criteria';
	const TYPE_API_CALL = 'api-call';
    
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
				];
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
				if(!empty($params["subscriber_event"])) {
					$this->updateData([
						'event' => $params["subscriber_event"]
					]);
				}
				break;
			case self::TYPE_CUSTOM_CRITERIA:
				if(!empty($params["custom_criteria"])) {
					$this->updateData([
						'criteria' => $params["custom_criteria"]
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
		$msg = '';
		switch ($this->event_type) {
			case self::TYPE_SPECIFIC_DATE:
				$msg = trans('messages.on_the_specific_date', [
					'date' => \Acelle\Library\Tool::dayStringFromTimestamp(\Acelle\Library\Tool::dateTimeFromString($this->getDataValue('date'))),
					'time' => \Acelle\Library\Tool::timeStringFromTimestamp(\Acelle\Library\Tool::dateTimeFromString($this->getDataValue('date')))
				]);
				break;
			case self::TYPE_WEEKLY_RECURRING:				
				
				break;
			case self::TYPE_MONTHLY_RECURRING:
				
				break;
			case self::TYPE_SUBSCRIBER_EVENT:
				
				break;
			case self::TYPE_CUSTOM_CRITERIA:
				
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
     * Email events.
     *
     * @return array
     */
    public static function emailEventOptions()
    {
        return [
			['value' => 'sent', 'text' => strtolower(trans('messages.sent'))],
			['value' => 'opened', 'text' => strtolower(trans('messages.opened'))],            
            ['value' => 'clicked', 'text' => strtolower(trans('messages.clicked'))],
        ];
    }
}
