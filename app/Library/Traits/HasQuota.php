<?php

namespace Acelle\Library\Traits;

use Exception;
use Acelle\Library\QuotaTracker;
use Closure;
use Carbon\Carbon;

trait HasQuota
{
    public function getQuotaTracker($name)
    {
        $quotaTracker = new QuotaTracker($this->getQuotaLogFile($name));
        return $quotaTracker;
    }
    /** Other related functions are enforced in the HasQuotaInterafce **/

    // public function getCreditsLogFile(string $name) : string;
    // public function getQuotaLogFile(string $name) : string;
    // public function getQuotaSettings(string $name) : ?array;

    public function getCreditsLogJson()
    {
        $filepath = $this->getCreditsLogFile();
        if (!file_exists($filepath)) {
            \Acelle\Helpers\ptouch($filepath);
        }

        $json = json_decode(file_get_contents($filepath), true) ?: [];
        return $json;
    }

    private function updateCreditsLog($json) {
        $filepath = $this->getCreditsLogFile();
        file_put_contents($filepath, json_encode($json));
    }

    public function getRemainingCredits($name) {
        $json = $this->getCreditsLogJson();

        if (!array_key_exists($name, $json)) {
            return self::QUOTA_UNLIMITED;
        }

        // This is the remaining credits
        return $json[$name];
    }

    public function addCredits($name, $count)
    {
        $json = $this->getCreditsLogJson();

        if (!array_key_exists($name, $json)) {
            $json[$name] = $count;
        } else {
            $json[$name] = $json[$name] + $count;
        }

        $this->updateCreditsLog($json);
    }

    public function setCredits($name, $count)
    {
        $json = $this->getCreditsLogJson();
        $json[$name] = $count;
        $this->updateCreditsLog($json);
    }

    public function withQuota($name, Closure $execute)
    {
        // Check available credits and quota
        // Count if passed, throw exception otherwise
        $limits = $this->getQuotaSettings($name);

        // Check quota allowance
        $now = Carbon::now();

        // Count quota use
        $this->getQuotaTracker($name)->count($now, $limits, function() use ($name) {
            // Take advantage of the lock to count remaining credits
            $this->updateRemainingCredits($name);
        });

        // Okie, execute
        $execute();
    }

    // MAIN
    public function updateRemainingCredits(string $name)
    {
        $json = $this->getCreditsLogJson();
        if (!array_key_exists($name, $json)) {
            throw new Exception('No credits information available, initialize it first by setCredits() or addCredits()');
        }

        $remaining = $json[$name];

        if ($remaining === self::QUOTA_UNLIMITED) {
            // QUOTA_UNLIMITED (-1) also means "unlimited"
            return;
        }

        if ($remaining === self::QUOTA_ZERO) {
            // QUOTA_ZERO (0) means "limit reached"
            throw new Exception("Credits remaining is already 0");
        }

        // Deduct remaining credits
        $json[$name] = $remaining - 1;
        $this->updateCreditsLog($json);
    }

    public function getCreditsUsed(string $name, Carbon $from = null, Carbon $to = null)
    {
        return $this->getQuotaTracker($name)->getCreditsUsed($from, $to);
    }
}
