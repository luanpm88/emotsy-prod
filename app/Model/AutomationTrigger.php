<?php

/**
 * AutomationTrigger class.
 *
 * Model for automation triggers
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

class AutomationTrigger extends Model
{
    // type_name
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
        'type_name', 'send_at', 'recurring_send_at', 'recurring_type', 'recurring_day_of_week',
        'recurring_only_first_week', 'recurring_month', 'recurring_day_of_month'
    ];
    
    /**
     * The validation rules for automation trigger.
     *
     * @var array
     */
    public function rules()
    {
        $rules = [];
        if($this->type_name == self::TYPE_SPECIFIC_DATE) {
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
        // send at
        if(!empty($params["send_day"]) && !empty($params["send_time"])) {
            $this->send_at = \Acelle\Library\Tool::systemTimeFromString($params["send_day"].' '.$params["send_time"], \Auth::user()->timezone);
        }        
    }
}
