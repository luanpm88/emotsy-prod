<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public static $itemsPerPage = 25;

    // belongs to customer
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
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
            while (self::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }

    public function scopeSearch($query, $request)
    {
        $query = $query->where('customer_id', '=', $request->user()->id);
        
        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('products.title', 'like', '%'.strtolower($keyword).'%');
                });
            }
        }

        return $query;
    }

    // get image path
    public function getImageDir()
    {
        $path = storage_path("app/users/" . $this->customer->user->uid);
        // create product images folder if not exist
        if(!\File::isDirectory($path)) {
            \File::makeDirectory($path, 0777, true, true);
        }

        $path .= '/products';
        // create product images folder if not exist
        if(!\File::isDirectory($path)) {
            \File::makeDirectory($path, 0777, true, true);
        }

        $path .= '/' . $this->uid;
        // create product images folder if not exist
        if(!\File::isDirectory($path)) {
            \File::makeDirectory($path, 0777, true, true);
        }

        return $path . '/';
    }

    // upload image
    public function uploadImage($url)
    {
        copy($url, $this->getImagePath());
    }

    // get image path
    public function getImagePath()
    {
        return $this->getImageDir() . 'default';
    }
}
