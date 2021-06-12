<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            $item->uid = uniqid();
        });
    }
}
