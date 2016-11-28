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
	const TYPE_ANNUAL_RECURRING = 'annual-recurring';
	const TYPE_LIST_SUBSCRIPTION = 'list-subscription';
	const TYPE_LIST_UNSUBSCRIPTION = 'list-unsubscription';
	const TYPE_EVENT_BASE = 'event-based';
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
     * The validation rules for automation trigger.
     *
     * @var array
     */
    public function rules()
    {
        $rules = [];
        if($this->event_type == self::TYPE_SPECIFIC_DATE) {
            $rules = [
                'send_day' => 'required',
                'send_time' => 'required',
            ];
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
            ['value' => self::TYPE_ANNUAL_RECURRING, 'text' => trans('messages.Annual_recurring')],
            ['value' => self::TYPE_LIST_SUBSCRIPTION, 'text' => trans('messages.Event_based_list_subscription')],
            ['value' => self::TYPE_LIST_UNSUBSCRIPTION, 'text' => trans('messages.Event_based_list_unsubscription')],
            ['value' => self::TYPE_EVENT_BASE, 'text' => trans('messages.Event_based_custom_list_subscriber_event')],
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
			case 1:
				echo "i equals 1";
				break;
			case 2:
				echo "i equals 2";
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
}
