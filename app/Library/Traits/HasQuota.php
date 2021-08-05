<?php

namespace Acelle\Library\Traits;

use Exception;
use Acelle\Library\QuotaTracker;

trait HasQuota
{
    /** Other related functions are enforced in the HasQuotaInterafce **/

    // public function getCreditsLimit(string $name) : ?int;
    // public function getCreditsLogFile(string $name) : string;
    // public function getQuotaSettings(string $name) : ?array;
    // public function getQuotaLogFile(string $name) : string;

    public function getCreditsUsed(string $name)
    {
        $json = json_decode(file_get_contents($this->getCreditsUsedLogFile()), true) ?: [];

        if (!array_key_exists($name, $json)) {
            return 0;
        } else {
            return $json[$name];
        }
    }

    public function withQuota($name, Closure $execute, array $otherSubjects)
    {
        $otherSubjects[] = $this;

        foreach ($subjects as $subject) {
            $subject->checkCredits($name);
            $subject->checkQuota($name);
        }

        // Okie, execute
        $execute();

        // Update credits used and tracker's log
        foreach ($subjects as $subject) {
            $subject->countCreditUsed($name);
            $subject->updateQuotaLog($name);
        }
    }

    public function checkCredits($name)
    {
        $creditsUsed = $this->getCreditsUsed($name);

        // Check credits used
        if ($creditsUsed >= $this->getCreditLimit($name)) {
            throw new Exception("You do not have enough credits left"); // or Callback here
        }
    }

    public function checkQuota($name, array $limits)
    {
        // Check quota allowance
        $quotaTracker = new QuotaTracker($this->getQuotaLogFile($name));
        if (!$quotaTracker->check($limits, $now = null, $failed)) {
        	var_dump($failed);
            throw new Exception("Quota limits reached"); // or Callback here
        }
    }

    // MAIN
    public function countCreditUsed(string $name)
    {
        $filepath = $this->getCreditsLogFile($name);
        if (!file_exists($filepath)) {
            \Acelle\Helpers\ptouch($filepath);
        }

        $json = json_decode(file_get_contents($filepath), true) ?: [];

        if (!array_key_exists($name, $json)) {
            $json[$name] = 1; // start from zero
        } else {
            $json[$name] = $json[$name] + 1;
        }

        file_put_contents($filepath, json_encode($json));
    }

    public function updateQuotaCounter(string $name)
    {
        $quotaTracker = new QuotaTracker($this->getQuotaLogFile($name), $this->getQuotaSettings($name));
        $quotaTracker->count();
    }
}
