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
use Acelle\Model\AutomationElement;
use Acelle\Model\Email;
use Acelle\Model\EmailLink;
use Acelle\Model\Subscriber;
use DB;
use Acelle\Library\Automation\Action;
use Acelle\Library\Automation\Trigger;
use Acelle\Library\Automation\Send;
use Acelle\Library\Automation\Wait;
use Acelle\Library\Automation\Evaluate;
use Carbon\Carbon;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Automation2 extends Model
{
    // Automation status
    const STATUS_ACTIVE = 'active';
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
        'name'
    ];

    /**
     * Association with mailList through mail_list_id column.
     */
    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    /**
     * Get all of the links for the automation.
     */
    public function emailLinks()
    {
        return $this->hasManyThrough('Acelle\Model\EmailLink', 'Acelle\Model\Email');
    }

    /**
     * Get all of the emails for the automation.
     */
    public function emails()
    {
        return $this->hasMany('Acelle\Model\Email');
    }

    /**
     * Association
     */
    public function autoTriggers()
    {
        return $this->hasMany('Acelle\Model\AutoTrigger');
    }

    /**
     * Association
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    /**
     * Association
     */
    public function timelines()
    {
        return $this->hasMany('Acelle\Model\Timeline');
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating automation.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (self::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
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
     * Create automation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'mail_list_uid' => 'required',
        ];
    }

    public function getElementById($id) {
        $data = $this->getElements()[$id];

        // search by id

        return $element;
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::where('customer_id', '=', $user->customer->id);

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
     * enable automation.
     */
    public function enable()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
    }

    /**
     * disable automation.
     */
    public function disable()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save();
    }

    /**
     * disable automation.
     */
    public function saveData($data)
    {
        $this->data = $data;
        $this->save();
    }

    /**
     * disable automation.
     */
    public function getData()
    {
        return isset($this->data) ? preg_replace('/"([^"]+)"\s*:\s*/', '$1:', $this->data) : "[]";
    }

    /**
     * get all tree elements.
     */
    public function getElements()
    {
        return isset($this->data) && !empty($this->data) ? json_decode($this->data) : [];
    }

    /**
     * get trigger.
     */
    public function getTrigger()
    {
        $elements = $this->getElements();

        return empty($elements) ? new AutomationElement(null) : new AutomationElement($elements[0]);
    }

    /**
     * get element by id.
     */
    public function getElement($id=null)
    {
        $elements = $this->getElements();

        foreach ($elements as $element) {
            if ($element->id == $id) {
                return new AutomationElement($element);
            }
        }

        return new AutomationElement(null);
    }

    /**
     * Get started time.
     */
    public function getStartedTime()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get contacts count.
     */
    public function getContactsCount()
    {
        return 2345;
    }

    /**
     * Get complete percent.
     */
    public function getCompletePercent()
    {
        return 56;
    }

    /**
     * Get complete percent.
     */
    public function getEmailsSentCount()
    {
        return 2500;
    }

    /**
     * Get delay options.
     */
    public function getDelayOptions()
    {
        return [
            ['text' => '5 minutes', 'value' => '5 minutes'],
            ['text' => '10 minutes', 'value' => '10 minutes'],
        ];
    }

    /**
     * Get delay or before options.
     */
    public function getDelayBeforeOptions()
    {
        return [
            ['text' => '-10 minutes', 'value' => '-10 minutes'],
            ['text' => '-5 minutes', 'value' => '-5 minutes'],
            ['text' => '5 minutes', 'value' => '5 minutes'],
            ['text' => '10 minutes', 'value' => '10 minutes'],
        ];
    }

    /**
     * Get email links options.
     */
    public function getEmailLinkOptions()
    {
        $data = [];

        foreach($this->emailLinks as $link) {
            $data[] = ['text' => $link->link, 'value' => $link->uid];
        }

        return $data;
    }

    /**
     * Get emails options.
     */
    public function getEmailOptions()
    {
        $data = [];

        foreach($this->emails as $email) {
            $data[] = ['text' => $email->subject, 'value' => $email->uid];
        }

        return $data;
    }

    /**
     * Initiate a trigger with a given subscriber.
     */
    public function initTrigger($subscriber)
    {
        $trigger = null;
        DB::transaction(function() use (&$trigger, $subscriber) {
            $trigger = $this->autoTriggers()->create();
            $trigger->subscriber()->associate($subscriber); // assign to subscriber_id field
            $trigger->updateWorkflow();
        });

        return $trigger;
    }

    /**
     * Scan for triggers updates and new triggers
     */
    public static function run()
    {
        // Step 1: check for new trigger
        $automations = self::where('status', self::STATUS_ACTIVE)->get();
        foreach ($automations as $automation) {
            $automation->logger()->info(sprintf('Checking automation "%s"', $automation->name));
            $automation->checkForNewTriggers();
            $automation->checkForExistingTriggersUpdate();
        }
    }

    /**
     * Check for new triggers
     */
    public function checkForNewTriggers()
    {
        $this->logger()->info(sprintf('NEW > Start checking for new trigger'));
        $type = 'list-subscription';
        switch ($type) {
            case 'list-subscription':
                $this->checkForListSubscription();
                break;
            case 'ElementAction':
                //...
                break;
            case 'ElementCondition':
                //...
                break;
            case 'ElementWait':
                //...
                break;
            default:
                throw new \Exception('Unknown Automation trigger type '. $type);
        }
        $this->logger()->info(sprintf('NEW > Finish checking for new trigger'));
    }

    /**
     * Check for existing triggers update
     */
    public function checkForExistingTriggersUpdate()
    {
        $this->logger()->info(sprintf('UPDATE > Start checking for trigger update'));
        foreach ($this->autoTriggers as $trigger) {
            $trigger->check();
        }
        $this->logger()->info(sprintf('UPDATE > Finish checking for trigger update'));
    }

    /**
     * Check for list-subscription events.
     */
    public function checkForListSubscription()
    {
        $now = Carbon::now();
        $subscribers = $this->getNewSubscribersToFollow();
        $this->logger()->info(sprintf('NEW > There are %s new subscriber(s) found', count($subscribers)));
        $i = 0;
        foreach ($subscribers as $subscriber) {
            $i += 1;
            $this->initTrigger($subscriber);
            $this->logger()->info(sprintf('NEW > (%s/%s) > Adding new trigger for %s', $i, count($subscribers), $subscriber->email));
        }
    }

    /**
     * Get previous event's opened messages to follow up.
     *
     * @return collection
     */
    public function getNewSubscribersToFollow()
    {
        // Boot performance with temporary table + index
        try {
            \DB::statement("DROP TEMPORARY TABLE `_new_subscribers_to_follow`");
        } catch (\Exception $ex) {
            // just ignore, in case 2 queries in the same MySQL connection session
        }

        \DB::statement(sprintf("
            CREATE TEMPORARY TABLE `_new_subscribers_to_follow` AS
            SELECT COALESCE(subscriber_id, 0) AS subscriber_id
            FROM %s WHERE automation2_id = %s;

            CREATE INDEX _new_subscribers_to_follow_index ON _new_subscribers_to_follow(subscriber_id);"
            , table('auto_triggers'), $this->id)
        );

        return Subscriber::whereRaw(table('subscribers.id').' NOT IN (SELECT subscriber_id FROM _new_subscribers_to_follow)')
                ->where('subscribers.created_at', '>=', $this->created_at)
                ->where('mail_list_id', $this->mailList->id)
                ->whereRaw(sprintf('COALESCE('.table('subscribers.subscription_type').", '') <> %s", db_quote(Subscriber::SUBSCRIPTION_TYPE_IMPORTED)))
                ->get();
    }

    public function logger()
    {
        $pid = getmypid();
        $formatter = new LineFormatter("[%datetime%] #{$pid} %channel%.%level_name%: %message%\n");
        $stream = new RotatingFileHandler(storage_path('logs/automation.log'), 0, Logger::INFO);
        $stream->setFormatter($formatter);

        $logger = new Logger($this->uid);
        $logger->pushHandler($stream);
        return $logger;
    }

    public function getTimelineBy($subscriber)
    {
        $trigger = $this->autoTriggers()->where('subscriber_id', $subscriber->id)->first();
        return $trigger->getTimeline();
    }

    public function getInsight()
    {
        $actions = json_decode($this->data, true);
        $insights = [];
        foreach ($actions as $action) {
            $insights[$action['id']] = $this->getActionStats($action);
        }

        return $insights;
    }

    public function getActionStats($action)
    {
        $total = $this->mailList->count();
        $count = 0;
        foreach ($this->autoTriggers as $trigger) {
            $count += ($trigger->isActionExecuted($action['id'])) ? 1 : 0;
        }
        // The following implementation is prettier but 'countBy' is supported in Laravel 5.8 only
        // $count = $this->autoTriggers()->countBy(function($trigger) {
        //    return $trigger->isActionExecuted($action['id']);
        // });

        $insight = [
            'count' => $count,
            'percentage' => ($total != 0) ? ($count / $total) : 0,
            'latest_activity' => $this->autoTriggers()->max('created_at'),
        ];

        return $insight;
    }

    public function getSummaryStats()
    {
        $total = $this->mailList->count();
        $involved = $this->autoTriggers()->count();
        $involvePercentage = ($total == 0) ? 0 : $involved / $total;

        $complete = 0;
        foreach ($this->autoTriggers as $trigger) {
            $complete += ($trigger->isComplete()) ? 1 : 0;
        }

        return [
            'total' => $total,
            'involed' => $involvePercentage,
            'complete' => $complete
        ];
    }

    public function getAction($attributes)
    {
        switch ($attributes['type']) {
            case 'ElementTrigger':
                $instance = new Trigger($attributes);
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

    public function getSubscribers($actionId = null)
    {
        $list = $this->mailList->subscribers();

        if (!is_null($actionId)) {
            $list->join('auto_triggers', 'auto_triggers.subscriber_id', '=', 'subscribers.id')
                 ->where('auto_triggers.executed_index', 'LIKE', '%' .$actionId. '%');
        }

        return $list;
    }
}