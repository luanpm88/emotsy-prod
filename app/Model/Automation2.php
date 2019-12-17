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
}