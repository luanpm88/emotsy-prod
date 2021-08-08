<?php

namespace Acelle\Library\Contracts;

interface HasQuota
{
    public function getCreditsLogFile() : string;
    public function getQuotaLogFile(string $name) : string;
    public function getQuotaSettings(string $name) : ?array;
}
