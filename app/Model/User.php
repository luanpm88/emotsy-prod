<?php

/**
 * User class.
 *
 * Model class for user
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

use Illuminate\Foundation\Auth\User as Authenticatable;

use Carbon\Carbon;
use Acelle\Library\Log as MailLog;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * The rules for validation via api.
     *
     * @var array
     */
    public function apiRules()
    {
        return array(
            'user_group_id' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->id.',id',
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
            'password' => 'required|min:5',
        );
    }
    
    /**
     * The rules for validation via api.
     *
     * @var array
     */
    public function apiUpdateRules($request)
    {
        $arr = [];
        
        if(isset($request->email)) {
            $arr['email'] = 'required|email|unique:users,email,'.$this->id.',id';
        }
        if(isset($request->first_name)) {
            $arr['first_name'] = 'required';
        }
        if(isset($request->last_name)) {
            $arr['last_name'] = 'required';
        }
        if(isset($request->timezone)) {
            $arr['timezone'] = 'required';
        }
        if(isset($request->language_id)) {
            $arr['language_id'] = 'required';
        }
        if(isset($request->password)) {
            $arr['password'] = 'min:5';
        }
        
        return $arr;
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
     * Associations.
     *
     * @var object | collect
     */
    public function customer()
    {
        return $this->hasOne('Acelle\Model\Customer');
    }
    
    public function admin()
    {
        return $this->hasOne('Acelle\Model\Admin');
    }
    
    public function reseller()
    {
        return $this->hasOne('Acelle\Model\Reseller');
    }
    
    public function systemJobs()
    {
        return $this->hasMany('Acelle\Model\SystemJob')->orderBy('created_at', 'desc');
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (User::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;

            // Add api token
            $item->api_token = str_random(60);
        });
    }

    /**
     * Get authenticate from file.
     *
     * @return string
     */
    public static function getAuthenticateFromFile()
    {
        $path = base_path('.authenticate');

        if (file_exists($path)) {
            $content = \File::get($path);
            $lines = explode("\n", $content);
            if (count($lines) > 1) {
                $demo = session()->get('demo');
                if (!isset($demo) || $demo == 'admin') {
                    return ['email' => $lines[0], 'password' => $lines[1]];
                } else {
                    return ['email' => $lines[0], 'password' => $lines[1]];
                }
            }
        }

        return ['email' => '', 'password' => ''];
    }
    
    /**
     * Send regitration activation email.
     *
     * @return string
     */
    public function sendActivationMail($name=null, $redirect_url=null) {
        $layout = \Acelle\Model\Layout::where('alias', 'registration_confirmation_email')->first();
        $token = $this->getToken();
        $redirect_url = urlencode($redirect_url);
        
        $layout->content = str_replace('{ACTIVATION_URL}', action('UserController@activate', ['token' => $token, 'redirect' => $redirect_url]), $layout->content);
        $layout->content = str_replace('{CUSTOMER_NAME}', $name, $layout->content);        
        
        \Mail::send('users.registration_confirmation_email', ['content' => $layout->content], function ($m) {
            $m->from(config("mail.from")["address"], config("mail.from")["name"]);

            $m->to($this->email, trans('messages.to_email_name'))->subject(trans('messages.registration_confirmation_email_subject'));
        });
    }
    
    /**
     * User activation.
     *
     * @return string
     */
    public function userActivation()
    {
        return $this->hasOne('Acelle\Model\userActivation');
    }
    
    /**
     * Create activation token for user.
     *
     * @return string
     */
    public function getToken()
    {
        $token = \Acelle\Model\UserActivation::getToken();
        
        $userActivation = $this->userActivation;

        if (!is_object($userActivation)) {                      
            $userActivation = new \Acelle\Model\UserActivation();
            $userActivation->user_id = $this->id;            
        }
        
        $userActivation->token = $token;
        $userActivation->save();        
        
        return $token;
    }
    
    /**
     * Set user is activated.
     *
     * @return boolean
     */
    public function setActivated()
    {
        $this->activated = true;
        $this->save();
    }
}
