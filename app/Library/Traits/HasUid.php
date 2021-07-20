<?php
 
namespace Acelle\Library\Traits;
 
trait HasUid {
 
    public static function boot()
    {
        parent::boot();

        // Create uid when creating item.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            if (is_null($item->uid)) {
                $item->uid = $uid;
            }
        });
    }

    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }
 
}