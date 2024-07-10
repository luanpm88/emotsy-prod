<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Acelle\Library\Traits\HasUid;

class TranslationPhrase extends Model
{
    use HasFactory;
    use HasUid;

    protected $fillable = [
        'key'
    ];
}
