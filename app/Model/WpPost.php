<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class WpPost extends Model
{
    protected $connection= 'wordpress';

    public static function scopeProducts($query)
    {
        $query = $query->where('post_type', '=', 'product')
            ->where('post_status', '!=', 'auto-draft');
    }
}