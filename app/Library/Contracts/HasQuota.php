<?php

namespace Acelle\Library\Contracts;

interface HasQuota
{
    public function getCreditsLimit(string $name) : ?int;
    public function getCreditsLogFile(string $name) : string;
    public function getQuotaSettings(string $name) : ?array;
    public function getQuotaLogFile(string $name) : string;
}
