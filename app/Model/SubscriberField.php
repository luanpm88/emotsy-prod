<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class SubscriberField extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field_id', 'subscriber_id', 'value'
    ];
}
