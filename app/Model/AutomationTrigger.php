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
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_name', 'send_at', 'recurring_send_at', 'recurring_type', 'recurring_day_of_week',
        'recurring_only_first_week', 'recurring_month', 'recurring_day_of_month'
    ];
}
