<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Model\Source;
use File;

class Product extends Model
{
    public static $itemsPerPage = 16;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['source_item_id'];

    // belongs to customer
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    // belongs to source
    public function source()
    {
        return $this->belongsTo('Acelle\Model\Source');
    }

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

    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    public static function scopeFilter($query, $attribute, $value)
    {
        $query->where($attribute, '=', $value);
    }

    public function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty(trim($keyword))) {
            foreach (explode(' ', trim($keyword)) as $k) {
                $query = $query->where(function ($q) use ($k) {
                    $q->orwhere('products.title', 'like', '%'.strtolower($k).'%');
                });
            }
        }

        return $query;
    }

    // upload image
    public function uploadImage($url)
    {
        copy($url, $this->getImagePath());
    }

    // get image path
    public function getImagePath()
    {
        return $this->customer->getProductsPath('product-image-' . $this->uid);
    }

    public function getImageUrl()
    {
        if (file_exists($this->getImagePath())) {
            $path = str_replace(storage_path(), '', $this->getImagePath());
            return route('public_assets', ['path' => \Acelle\Library\StringHelper::base64UrlEncode($path)]);
        } else {
            return \URL::asset('images/no-product-image.png');
        }
    }
}
