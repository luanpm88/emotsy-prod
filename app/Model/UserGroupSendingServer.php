<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class UserGroupSendingServer extends Model
{
    /**
     * Associations.
     *
     * @var object | collect
     */
    public function user_group()
    {
        return $this->belongsTo('Mailixa\Model\UserGroup');
    }
    
    public function sending_server()
    {
        return $this->belongsTo('Mailixa\Model\SendingServer');
    }
}
