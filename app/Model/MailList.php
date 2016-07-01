<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class MailList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'default_subject', 'from_email', 'from_name', 'remind_message', 'send_to', 'email_daily', 'email_subscribe', 'email_unsubscribe', 'send_welcome_email', 'unsubscribe_notification'
    ];
    
    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
        'from_email' => 'required',
        'from_name' => 'required',
        'remind_message' => 'required',
        'contact.company' => 'required',
        'contact.address_1' => 'required',
        'contact.country_id' => 'required',
        'contact.state' => 'required',
        'contact.city' => 'required',
        'contact.zip' => 'required',
        'contact.phone' => 'required',
        'contact.email' => 'required|email',
        'contact.url' => 'url',
        'email_subscribe' => 'regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
        'email_unsubscribe' => 'regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
        'email_daily' => 'regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
    );
    
    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;
    
    /**
     * Associations.
     *
     * @var object | collect
     */ 
    public function fields()
    {
        return $this->hasMany('Mailixa\Model\Field');
    }
    
    public function segments()
    {
        return $this->hasMany('Mailixa\Model\Segment');
    }
    
    public function pages()
    {
        return $this->hasMany('Mailixa\Model\Page');
    }
    
    public function page($layout)
    {
        return $this->pages()->where("layout_id", $layout->id)->first();
    }
    
    public function contact()
    {
        return $this->belongsTo('Mailixa\Model\Contact');
    }
    
    public function subscribers()
    {
        return $this->hasMany('Mailixa\Model\Subscriber');
    }
    
    public function campaigns()
    {
        return $this->hasMany('Mailixa\Model\Campaign');
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();        
        
        // Create uid when creating list.
        static::creating(function($item)
        {
            // Create new uid
            $uid = uniqid();
            while(MailList::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
            
            // Update custom order
            MailList::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
        });
        
        // Create uid when list created.
        static::created(function($item)
        {
            //  Create list default fields
            $item->createDefaultFieds();
        });
    }
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return MailList::select("*");
    }
    
    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = MailList::where("user_id", "=", $user->id);
        
        // Keyword
        if(!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%' . $request->keyword . '%');
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
        $query = MailList::filter($request);
        
        $query = $query->orderBy($request->sort_order, $request->sort_direction);
        
        return $query;
    }
    
    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return MailList::where("uid", "=", $uid)->first();
    }
    
    /**
     * Get all fields.
     *
     * @return object
     */
    public function getFields()
    {
        return $this->fields()->orderBy("custom_order");
    }
    
    /**
     * Create default fields for list.
     *
     * @return void
     */
    public function createDefaultFieds()
    {
        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.email'),
                            'tag' => "EMAIL",
                            'required' => true,
                            'visible' => true,
                        ]);
        
        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.first_name'),
                            'tag' => \Mailixa\Model\Field::formatTag(trans('messages.first_name_tag')),
                            'required' => false,
                            'visible' => true,
                        ]);
        
        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.last_name'),
                            'tag' => \Mailixa\Model\Field::formatTag(trans('messages.last_name_tag')),
                            'required' => false,
                            'visible' => true,
                        ]);
    }
    
    /**
     * Get email field.
     *
     * @return object
     */
    function getEmailField()
    {
        return $this->getFieldByTag("EMAIL");
    }
    
    /**
     * Get field by tag.
     *
     * @return object
     */
    function getFieldByTag($tag)
    {
        return $this->fields()->where("tag", "=", $tag)->first();
    }

    /**
     * Get field by tag.
     *
     * @return object
     */
    function getActiveSubscribers()
    {
        return $this->subscribers()->where("status", 'active')->get();
    }
    
    /**
     * Get field rules.
     *
     * @return object
     */
    function getFieldRules()
    {
        $rules = [];
        foreach($this->getFields as $field) {
            if($field->tag == 'EMAIL') {
                $rules[$field->tag] = 'required|email';
            } elseif ($field->required) {
                $rules[$field->tag] = 'required';
            }            
        }
        return $rules;
    }
    
    /**
     * Check if a email is exsit.
     *
     * @param string the email.
     *
     * @return boolean
     */
    public function checkExsitEmail($email)
    {
        $valid = !filter_var($email, FILTER_VALIDATE_EMAIL) === false && !empty($email) && $this->subscribers()->where("email", "=", $email)->count() == 0;
        return $valid;
    }
    
    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions($user = null)
    {
        $query = MailList::getAll();
        if(is_object($user)) {
            $query = $query->where("user_id", "=", $user->id);
        }
        $options = $query->orderBy("name")->get()->map(function ($item) {
            return ['value' => $item->uid, 'text' => $item->name . " (" . $item->subscribers()->count() . " " . strtolower(trans('messages.subscribers')) . ")"];
        });
        return $options;
    }
    
    /**
     * Get segments select options.
     *
     * @return array
     */
    public function getSegmentSelectOptions()
    {
        $options = $this->segments->map(function ($item) {
            return ['value' => $item->uid, 'text' => $item->name . " (" . $item->subscribers()->count() . " " . strtolower(trans('messages.subscribers')) . ")"];
        });
        
        return $options;
    }
    
    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function unsubscribeCount()
    {
        return $this->subscribers()->where("status", "=", "unsubscribed")->count();
    }
    
    /**
     * Unsubscribe rate.
     *
     * @return array
     */
    public function unsubscribeRate()
    {
        return round(($this->unsubscribeCount()/$this->subscribers()->count())*100, 1);
    }
    
    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function subscribeCount()
    {
        return $this->subscribers()->where("status", "=", "subscribed")->count();
    }
    
    /**
     * Unsubscribe rate.
     *
     * @return array
     */
    public function subscribeRate()
    {
        return round(($this->subscribeCount()/$this->subscribers()->count())*100, 1);
    }
    
    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function unconfirmedCount()
    {
        return $this->subscribers()->where("status", "=", "unconfirmed")->count();
    }

}
