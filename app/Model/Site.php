<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;
use Acelle\Library\Traits\HasTemplate;

class Site extends Model
{
    use HasFactory;
    use HasUid;
    use HasTemplate;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public static function scopeSearch($query, $keyword)
    {
        // Keyword
        if (!empty(trim($keyword))) {
            foreach (explode(' ', trim($keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('sites.name', 'like', '%'.$keyword.'%');
                });
            }
        }
    }

    public static function newDefault()
    {
        $site = new static();
        $site->status = static::STATUS_ACTIVE;

        return $site;
    }

    public function saveSite($name, $type)
    {
        // fill
        $this->name = $name;
        $this->type = $type;

        $validator = \Validator::make($this->getAttributes(), [
            'name' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return [false, $validator->errors()];
        }

        $this->save();

        //
        if ($this->is_default) {
            $this->setDefault();
        }

        return [true, $validator->errors()];

    }

    public function getThumbUrl()
    {
        if (is_null($this->uid)) {
            throw new Exception('Cannot getThumbUrl(), template does not have a UID, cannot transform content');
        }

        if ($this->template->getThumbName()) {
            return \Acelle\Helpers\generatePublicPath($this->template->getStoragePath($this->template->getThumbName())) . '?v=' . filemtime($this->template->getStoragePath($this->template->getThumbName()));
        } else {
            return url('images/placeholder.jpg');
        }
    }

    public function isInactive()
    {
        return $this->status == static::STATUS_INACTIVE;
    }

    public function isActive()
    {
        return $this->status == static::STATUS_ACTIVE;
    }
}
