<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    const RESULT_DELIVERABLE = 'deliverable';
    const RESULT_UNDELIVERABLE = 'undeliverable';
    const RESULT_UNKNOWN = 'unknown';
    const RESULT_RISKY = 'risky';


}
