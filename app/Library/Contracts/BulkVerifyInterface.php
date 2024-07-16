<?php

namespace Acelle\Library\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Closure;

interface BulkVerifyInterface
{
    public function bulkSubmit(Builder $subscriberQuery): string;
    public function bulkCheck(string $token, Closure $doneCallback, Closure $waitCallback) : bool;
}
