<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Model\Source;
use File;
use Acelle\Library\Traits\HasUid;

class Product extends Model
{
    use HasUid;

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

    public function scopeFilter($query, $attribute, $value)
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
            return \Acelle\Helpers\generatePublicPath($this->getImagePath());
        } else {
            return \URL::asset('images/no-product-image.png');
        }
    }
}
