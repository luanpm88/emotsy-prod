<?php

/**
 * Admin class.
 *
 * Model class for admin
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

class Admin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'timezone', 'language_id', 'color_scheme'
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        return array(
            'email' => 'required|email|unique:users,email,'.$this->user_id.',id',
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
            'password' => 'confirmed|min:5',
        );
    }
    
    /**
     * Admin email.
     *
     * @return string
     */
    public function email()
    {
        return (is_object($this->user) ? $this->user->email : "" );
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
    public function contact()
    {
        return $this->belongsTo('Acelle\Model\Contact');
    }

    public function user()
    {
        return $this->belongsTo('Acelle\Model\User');
    }

    public function adminGroup()
    {
        return $this->belongsTo('Acelle\Model\AdminGroup');
    }
    
    public function customers()
    {
        return $this->hasMany('Acelle\Model\Customer', "creator_id", "user_id");
    }
    
    public function resellers()
    {
        return $this->hasMany('Acelle\Model\Reseller');
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
            while (Admin::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }
    
    /**
     * Display admin name: first_name last_name.
     *
     * @var string
     */
    public function displayName()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Upload and resize avatar.
     *
     * @var void
     */
    public function uploadImage($file)
    {
        $path = 'app/admins/';
        $upload_path = storage_path($path);
        
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        $filename = 'avatar-'.$this->id.'.'.$file->getClientOriginalExtension();

        // save to server
        $file->move($upload_path, $filename);

        // create thumbnails
        $img = \Image::make($upload_path.$filename);
        $img->fit(120, 120)->save($upload_path.$filename.'.thumb.jpg');

        return $path.$filename;
    }

    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function imagePath()
    {
        if (!empty($this->image) && !empty($this->id)) {
            return storage_path($this->image).'.thumb.jpg';
        } else {
            return '';
        }
    }

    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function removeImage()
    {
        if (!empty($this->image) && !empty($this->id)) {
            $path = storage_path($this->image);
            if (is_file($path)) {
                unlink($path);
            }
            if (is_file($path.'.thumb.jpg')) {
                unlink($path.'.thumb.jpg');
            }
        }
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return Admin::select('*');
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $query = self::select('admins.*')
                        ->leftJoin('admin_groups', 'admin_groups.id', '=', 'admins.admin_group_id');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('admins.first_name', 'like', '%'.$keyword.'%')
                        ->orWhere('admin_groups.name', 'like', '%'.$keyword.'%')
                        ->orWhere('admins.last_name', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            if (!empty($filters['admin_group_id'])) {
                $query = $query->where('admins.admin_group_id', '=', $filters['admin_group_id']);
            }
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
     * Get admin setting.
     *
     * @return string
     */
    public function getOption($name)
    {
        return $this->adminGroup->getOption($name);
    }
    
    /**
     * Get admin permission.
     *
     * @return string
     */
    public function getPermission($name)
    {
        return $this->adminGroup->getPermission($name);
    }    
    
    /**
     * Get user's color scheme.
     *
     * @return string
     */
    public function getColorScheme()
    {
        if (!empty($this->color_scheme)) {
            return $this->color_scheme;
        } else {
            return \Acelle\Model\Setting::get('backend_scheme');
        }
    }
    
    /**
     * Color array.
     *
     * @return array
     */
    public static function colors($default)
    {
        return [
            ['value' => '', 'text' => trans('messages.system_default')],
            ['value' => 'blue', 'text' => trans('messages.blue')],
            ['value' => 'green', 'text' => trans('messages.green')],
            ['value' => 'brown', 'text' => trans('messages.brown')],
            ['value' => 'pink', 'text' => trans('messages.pink')],
            ['value' => 'grey', 'text' => trans('messages.grey')],
            ['value' => 'white', 'text' => trans('messages.white')],
        ];
    }
    
    /**
     * Disable customer.
     *
     * @return boolean
     */
    public function disable()
    {
        $this->status = 'inactive';
        return $this->save();
    }
    
    /**
     * Enable customer.
     *
     * @return boolean
     */
    public function enable()
    {
        $this->status = 'active';
        return $this->save();
    }
    
    /**
     * Get recent resellers.
     *
     * @return collect
     */
    public function recentResellers()
    {
        return $this->resellers()->orderBy("created_at", "DESC")->limit(5)->get();
    }
}
