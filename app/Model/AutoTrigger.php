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

    // Update the workflow structure to trigger
    public function updateFromAutomation()
    {
        $json = json_decode(is_null($this->data) ? [] : $this->data);
        $actions = json_decode($automtion2->data, true);
        foreach ($actions as $action) {
            $json[$action['id']] = $action;
        }

        $this->data = json_encode($json);
        $this->save();
    }

    // getLatestAction from trigger
    public function getLatestAction()
    {
        $action = null;
        $this->getActions(function ($instance) use (&$action) {
            if ($instance->isLatest()) {
                $action = $instance;
            }
        });

        return $action;
    }

    // Mark the next action as "latest" and return it
    public function moveToNextAction()
    {
        $nextId = $this->getLatestAction()->getNextActionId();
        $selected = null;
        $this->getActions(function ($instance) use ($nextId, &$selected) {
            if ($instance->getId() == $nextId) {
                $instance->markAsLatest(true);
                $selected = $instance;
            } else {
                $instance->markAsLatest(false);
            }
            $this->updateJson($instance);
        });

        return $selected;
    }

    public function check()
    {
        $last = $this->getLatestAction();

        while ($last->execute() == true) { // true means: done with this step, should proceed next
            //var_dump($last->toJson());
            $this->updateJson($last); // update 'last_checked_at' field of Wait for example
            $last = $this->moveToNextAction();
            if ($last == null) {
                break;
            }
        }

        $this->updateJson($last); // update 'last_checked_at' field of Wait for example
    }

    public function updateJson($action)
    {
        var_dump($action->toJson());
        $json = json_decode($this->data, true);
        $json[$action->getId()] = $action->toJson();
        $this->data = json_encode($json);
        $this->save();
    }

    public function getActions($callback)
    {
        $json = json_decode($this->data, true);

        foreach ($json as $key => $attributes) {
            $instance = null;
            $attributes['id'] = $key;
            switch ($attributes['type']) {
                case 'ElementTrigger':
                    $instance = new Evaluate($attributes);
                    break;
                case 'ElementAction':
                    $instance = new Send($attributes);
                    break;
                case 'ElementCondition':
                    $instance = new Evaluate($attributes);
                    break;
                case 'ElementWait':
                    $instance = new Wait($attributes);
                    break;
                default:
                    var_dump($attributes['type']);
                    1 / 0;
            }

            $callback($instance);
        }
    }

    // for debugging only
    public function getTrigger()
    {
        $trigger = null;
        $this->getActions(function ($e) use (&$trigger) {
            if ($e->getType() == 'ElementTrigger') {
                $trigger = $e;
            }
        });

        return  $trigger;
    }
}

/*

$f = AutoTrigger::first();$x = $f->getLatestAction()
$s = '{
"trigger": {"title":"New Subscribers!", "type": "ElementTrigger", "child": "welcome", "options": {"test": "123"}, "latest": true},
"welcome": {"title":"Send a Welcome email", "type": "ElementAction", "child": "welcome2"},
"welcome2": {"title":"Send a Welcome email", "type": "ElementAction", "child": "wait"},
"wait": {"title":"Wait for a while", "type": "ElementWait", "child": "condition"},
"condition": {"title":"Send a Welcome email", "type": "ElementWait", "child": "condition"}
}';
$f->data = $s;
$f->save();


*/
