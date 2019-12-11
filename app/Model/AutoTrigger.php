<?php

/**
 * Automation Event Trigger class.
 *
 * Model class for logging triggered events
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
use Acelle\Library\Automation\Send;
use Acelle\Library\Automation\Wait;
use Acelle\Library\Automation\Evaluate;

class AutoTrigger extends Model
{
    protected $fillable = [
        //
    ];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * Associations.
     *
     * @return the associated subscriber
     */
    public function subscriber()
    {
        return $this->belongsTo('Acelle\Model\Subscriber');
    }

    /**
     * Associations.
     *
     * @return the associated automation2
     */
    public function automation2()
    {
        return $this->belongsTo('Acelle\Model\Automation2');
    }

    public function getLastAction()
    {
        return new Send([]);
    }

    // mark the next action as "latest" and return it
    public function moveToNextAction() {
        return $this->markLastestAction($this->getLastAction()->getNextId());
    }

    public function check()
    {
        $last = $this->getLastAction();
        while ($last->execute()) {
            $last = $this->moveToNextAction();
        }
    }

    private function markLastestAction($id)
    {

    }
}
