<?php

namespace Mailixa\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'first_name', 'last_name', 'email', 'timezone', 'language_id'
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
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'email' => 'required|email',
        'first_name' => 'required',
        'last_name' => 'required',
        'timezone' => 'required',
        'language_id' => 'required',
        'password' => 'confirmed|min:5',
    );
    
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
            while(User::where('uid','=',$uid)->count() > 0)
            {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }
    
    /**
     * Display user name: first_name last_name.
     *
     * @var string
     */
    public function displayName()
    {
        return $this->first_name . " " . $this->last_name;
    }
    
    /**
     * Upload and resize avatar.
     *
     * @var void
     */
    public function uploadImage($file) {
        $path = 'upload/user/image/';
        $upload_path = storage_path($path);
        $filename = "user-" . $this->id . "." . $file->getClientOriginalExtension();
        
        // save to server
        $file->move($upload_path, $filename);
        
        // create thumbnails
        $img = \Image::make($upload_path . $filename);
        $img->fit(100, 100)->save($upload_path . $filename . '.thumb.jpg');
  
        return $path . $filename;
    }
    
    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function imagePath() {
        if(!empty($this->image) && !empty($this->id)) {
            return storage_path($this->image).".thumb.jpg";
        } else {
            return "";
        }
    }
    
    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function removeImage() {
        if(!empty($this->image) && !empty($this->id)) {
            $path = storage_path($this->image);
            unlink($path);
            unlink($path.".thumb.jpg");
        }
    }
}
