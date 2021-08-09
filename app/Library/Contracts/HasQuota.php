<?php

namespace Acelle\Library\Contracts;

interface HasQuota
{
    const QUOTA_ZERO = 0;
    const QUOTA_UNLIMITED = -1;

    public function getCreditsLogFile() : string;
    public function getQuotaLogFile(string $name) : string;
    public function getQuotaSettings(string $name) : ?array;
}
