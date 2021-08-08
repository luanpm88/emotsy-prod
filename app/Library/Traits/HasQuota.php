<?php

namespace Acelle\Library\Traits;

use Exception;
use Acelle\Library\QuotaTracker;
use Closure;
use Carbon\Carbon;

trait HasQuota
{
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
            throw new Exception("Credits log for `{$name}` is not yet initialized");
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

    public function withQuota($name, Closure $execute, array $subjects = [])
    {
        // Check credits and quota of the caller as well as the other $subjects
        $this->countQuota($name, function() use ($name, $subjects) {
            $this->countCreditsUsed($name);

            //
            foreach ($subjects as $subject) {
                $subject->countQuota($name, function() {
                    $subject->countCreditsUsed($name);
                });
            }
        });

        // Okie, execute
        $execute();
    }

    // Closure is for checking sending credits
    public function countQuota(string $name, Closure $callback = null)
    {
        $limits = $this->getQuotaSettings($name);
        // Check quota allowance
        $quotaTracker = new QuotaTracker($this->getQuotaLogFile($name));
        $quotaTracker->count(Carbon::now(), $limits, $callback);
    }

    // MAIN
    public function countCreditsUsed(string $name)
    {
        $json = $this->getCreditsLogJson();
        if (!array_key_exists($name, $json)) {
            throw new Exception("Credits log for `{$name}` is not yet initialized (make sure addCredits() or setCredits() is previously executed");
        }

        $used = $json[$name];
        if ($used === 0) {
            throw new Exception("Credits remaining is already 0");
        }

        $json[$name] = $json[$name] - 1;
        $this->updateCreditsLog($json);
    }
}
