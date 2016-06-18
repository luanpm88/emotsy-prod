<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'alias', 'content'
    ];
    
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
            while(Layout::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }
    
    /**
     * Create default layouts.
     *
     * @var array
     */
    public static function createDefaultLayouts()
    {
        //foreach(Layout::all() as $layout) {
        //    $layout->delete();
        //}
        // Sign-up form layout
        $layout = new Layout();
        $layout->name = "Unsubscribe form";
        $layout->alias = "profile_update_email_sent";
        $layout->content = "...";
        $layout->save();
    }
    
    public function tags()
    {
        switch ($this->alias) {
            case "sign_up_form":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                            array("name" => "[FIELDS]", "required" => true),
                            array("name" => "[SUBSCRIBE_BUTTON]", "required" => true),
                        );
                break;
            case "sign_up_thankyou_page":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                        );
                break;
            case "sign_up_confirmation_email":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                            array("name" => "[SUBSCRIBE_CONFIRM_URL]", "required" => true),
                        );
                break;
            case "update-profile":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                            array("name" => "[LIST_FIELDS]", "required" => true),
                            array("name" => "[SUBMIT_BUTTON]", "required" => true),
                        );
                break;
            case "unsubscribe-form":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                            array("name" => "[UNSUBSCRIBE_EMAIL_FIELD]", "required" => true),
                            array("name" => "[SUBMIT_BUTTON]", "required" => true),
                        );
                break;
            case "unsubscribe-confirm":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                            array("name" => "[SUBSCRIBE_URL]", "required" => false),
                        );
                break;
            case "subscribe-confirm-email":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                            array("name" => "[COMPANY_NAME]", "required" => false),
                            array("name" => "[CURRENT_YEAR]", "required" => false),
                            array("name" => "[SUBSCRIBE_CONFIRM_URL]", "required" => true),
                        );
                break;
            case "unsubscribe-confirm-email":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                            array("name" => "[COMPANY_NAME]", "required" => false),
                            array("name" => "[CURRENT_YEAR]", "required" => false),
                            array("name" => "[UNSUBSCRIBE_CONFIRM_URL]", "required" => true),
                        );
                break;
            case "welcome-email":
                $tags = array(
                            array("name" => "[LIST_NAME]", "required" => false),
                            array("name" => "[UPDATE_PROFILE_URL]", "required" => false),
                            array("name" => "[COMPANY_NAME]", "required" => false),
                            array("name" => "[CURRENT_YEAR]", "required" => false),
                            array("name" => "[UNSUBSCRIBE_URL]", "required" => false),
                            array("name" => "[COMPANY_FULL_ADDRESS]", "required" => false),
                        );
                break;
            default:
                $tags = array();
        }
        
        return $tags;
    }
}
