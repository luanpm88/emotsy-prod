<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'first_name', 'last_name', 'address_1', 'address_2', 'city', 'zip', 'url', 'company', 'phone', 'state', 'country_id',
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
        'address_1' => 'required',
        'city' => 'required',
        'zip' => 'required',
        'url' => 'required|url',
        'company' => 'required',
        'phone' => 'required',
        'state' => 'required',
        'country_id' => 'required',
    );

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
            while (Contact::where('uid', '=', $uid)->count() > 0) {
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
    public function country()
    {
        return $this->belongsTo('Mailixa\Model\Country');
    }

    /**
     * Display contact name.
     *
     * @var string
     */
    public function name()
    {
        return $this->first_name.' '.$this->last_name;
    }
}
