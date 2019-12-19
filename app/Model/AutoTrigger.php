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
use Acelle\Library\Automation\Action;
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
            $this->updateAction($instance);
        });

        return $selected;
    }

    public function check()
    {
        $last = $this->getLatestAction();

        if ($last == null) {
            $trigger = $this->getTrigger();
            $trigger->markAsLatest(true);
            $this->updateAction( $trigger );
            $last = $trigger;
        }

        while ($last->execute($this) == true) { // true means: done with this step, should proceed next
            $this->updateAction($last); // update 'last_checked_at' field of Wait for example
            $last = $this->moveToNextAction();
            if ($last == null) {
                break;
            }
        }

        if (!is_null($last)) {
            $this->updateAction($last); // update 'last_checked_at' field of Wait for example
        }

    }

    public function updateAction($action)
    {
        $json = $this->getJson();

        if (array_key_exists($action->getId(), $json)) {
            $current = $json[$action->getId()];
            $updated = array_merge($current, $action->toJson());
            $json[$action->getId()] = $updated;
        } else {
            $json[$action->getId()] = $action->toJson();
        }

        $this->data = json_encode($json);
        $this->save();
    }

    public function getActions($callback)
    {
        $json = $this->getJson();

        foreach ($json as $key => $attributes) {
            $attributes['id'] = $key;
            $instance = $this->getAction($attributes);
            $callback($instance);
        }
    }

    public function getAction($attributes)
    {
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
                throw new \Exception('Unknown Action type '. $attributes['type']);
        }

        return $instance;
    }

    public function updateWorkflow()
    {
        $origins = json_decode($this->automation2->data, true);

        foreach ($origins as $origin) {
            $action = $this->getAction($origin);
            $this->updateAction($action);
        }
    }

    public function getJson()
    {
        return is_null($this->data) ? [] : json_decode($this->data, true);
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

    public function logger()
    {
        return $this->automation2->logger();
    }
}
