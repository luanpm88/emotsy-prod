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
        'name', 'email', 'first_name', 'last_name', 'email', 'image', 'timezone', 'language_id'
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
     * Display user name: first_name last_name.
     *
     * @var string
     */
    public function displayName()
    {
        return $this->first_name . " " . $this->last_name;
    }
}
