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
                            array("name" => "{FIELDS}", "required" => true),
                            array("name" => "{SUBSCRIBE_BUTTON}", "required" => true),
                        );
                break;
            case "sign_up_thankyou_page":
                $tags = array(
                        );
                break;
            case "sign_up_confirmation_email":
                $tags = array(
                            array("name" => "{SUBSCRIBE_CONFIRM_URL}", "required" => true),
                        );
                break;
            case "sign_up_confirmation_thankyou":
                $tags = array(
                        );
                break;
            case "sign_up_welcome_email":
                $tags = array(
                            array("name" => "{UNSUBSCRIBE_URL}", "required" => true),
                        );
                break;
            case "unsubscribe_form":
                $tags = array(
                            array("name" => "{EMAIL_FIELD}", "required" => true),
                            array("name" => "{UNSUBSCRIBE_BUTTON}", "required" => true),
                        );
                break;
            case "sign_up_confirmation_thankyou":
                $tags = array(
                        );
                break;
            case "unsubscribe_success_page":
                $tags = array(
                        );
                break;
            case "unsubscribe_goodbye_email":
                $tags = array(
                        );
                break;
            case "profile_update_email_sent":
                $tags = array(
                        );
                break;
            case "profile_update_email":
                $tags = array(
                            array("name" => "{UPDATE_PROFILE_URL}", "required" => true),
                        );
                break;
            case "profile_update_form":
                $tags = array(
                            array("name" => "{FIELDS}", "required" => true),
                            array("name" => "{UPDATE_PROFILE_BUTTON}", "required" => true),
                            array("name" => "{UNSUBSCRIBE_URL}", "required" => true),
                        );
                break;
            case "profile_update_success_page":
                $tags = array(
                        );
                break;
            default:
                $tags = array();
        }
        
        return $tags;
    }
}
