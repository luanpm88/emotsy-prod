<?php

namespace Acelle\Library\Traits;

use Exception;
use Acelle\Library\QuotaTracker;

trait HasQuota
{
    const QUOTA_ZERO_CREDITS = 0;
    /** Other related functions are enforced in the HasQuotaInterafce **/

    // public function getCreditsLogFile(string $name) : string;
    // public function getQuotaLogFile(string $name) : string;
    // public function getQuotaSettings(string $name) : ?array;

    public function getCreditsLogJson()
    {
        $filepath = $this->getCreditsLogFile($name);
        if (!file_exists($filepath)) {
            \Acelle\Helpers\ptouch($filepath);
        }

        $json = json_decode(file_get_contents($filepath), true) ?: [];
        return $json;
    }

    private function updateCreditsLog($json) {
        file_put_contents($this->filepath, json_encode($json));
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

        $this->updateCreditsLog($json)
    }

    public function withQuota($name, Closure $execute, array $subjects = [])
    {
        $subjects[] = $this;

        foreach ($subjects as $subject) {
            $subject->checkCredits($name);
        }

        // Okie, execute
        $execute();

        // Update credits used and tracker's log
        foreach ($subjects as $subject) {
            $subject->countCreditsUsed($name);
        }
    }

    public function checkCredits($name)
    {
        $creditsUsed = $this->getRemainingCredits($name);

        // Check credits used
        if ($creditsUsed === self::QUOTA_ZERO_CREDITS) {
            throw new Exception("There is no `{$name}` credits left"); // or Callback here
        }
    }

    public function checkQuota($name, array $limits)
    {
        // Check quota allowance
        $quotaTracker = new QuotaTracker($this->getQuotaLogFile($name));
        if (!$quotaTracker->check($now = null, $limits, $failed)) {
            throw new Exception("Quota limits reached"); // or Callback here
        }
    }

    // MAIN
    public function countCreditsUsed(string $name)
    {
        $json = $this->getCreditsLogJson();
        if (!array_key_exists($name, $json)) {
            throw new Exception("Credits log for `{$name}` is not yet initialized (make sure addCredits() or setCredits() is previously executed");
        }

        $used = $json[$name];
        if ($used === self::QUOTA_ZERO_CREDITS) {
            throw new Exception("Credits remaining is already 0");
        }

        $json[$name] = $json[$name] - 1;
    }

    public function updateQuotaCounter(string $name)
    {
        $quotaTracker = new QuotaTracker($this->getQuotaLogFile($name), $this->getQuotaSettings($name));
        $quotaTracker->count();
    }
}
