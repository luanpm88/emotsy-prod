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
        'name', 'from_email', 'from_name', 'remind_message', 'send_to', 'email_daily', 'email_subscribe', 'email_unsubscribe'
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
        'send_to' => 'required',
        'remind_message' => 'required',
        'contact.company' => 'required',
        'contact.address_1' => 'required',
        'contact.country_id' => 'required',
        'contact.state' => 'required',
        'contact.city' => 'required',
        'contact.zip' => 'required',
        'contact.phone' => 'required',
    );
    
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
        });
    }
    
    /**
     * Associations.
     *
     * @var object | collect
     */ 
    public function contact()
    {
        return $this->belongsTo('Mailixa\Model\Contact');
    }
    
    /**
     * Get all languages.
     *
     * @return collect
     */
    public static function getAll()
    {
        return MailList::all();
    }
}
