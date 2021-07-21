<?php
 
namespace Acelle\Library\Traits;

trait HasUid
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (is_null($item->uid)) {
                $item->uid = uniqid();
            }
        });
    }

    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }
}
