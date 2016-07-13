<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class SendingServerAmazonSmtp extends SendingServerAmazon
{
    protected $table = 'sending_servers';
}
