<?php

namespace Acelle\Library\Traits;

use Exception;

trait HasQuota
{
    // Subject to implement
    public function getCreditLimit($name)
    {
        throw new Exception('Subject must overwrite this method');
    }

    // Subject to implement
    public function getQuotaSettings($name)
    {
        throw new Exception('Subject must overwrite this method');

        return [
            [
                'name' => 'Emails per hour',
                'period' => '1 hour',
                'limit' => 1000
            ], [
                'name' => 'Emails per day',
                'period' => '1 day',
                'limit' => 5000
            ]
        ];
    }

    // Subject to implement
    public function getQuotaTrackerLogFile($name)
    {
        $filepath = $this->customer->getStoragePath("quota/{$name}-tracker-log");
        return $filepath;
    }

    // Subject to implement
    public function getCreditsUsedLogFile($name)
    {
        $filepath = $this->customer->getStoragePath("quota/{$name}-credits-used");

        if (!file_exists($filePath)) {
            touch($filepath);
        }

        return $filepath;
    }

    // MAIN

    public function countCreditUsed(string $name)
    {
        $json = json_decode(file_get_contents($this->getCreditsUsedLogFile()), true) ?: [];

        if (!array_key_exists($name, $json)) {
            $json[$name] = 1; // start from zero
        } else {
            $json[$name] = $json[$name] + 1;
        }

        file_put_contents($this->getCreditsUsedLogFile(), json_encode($json));
    }

    public function getCreditsUsed(string $name)
    {
        $json = json_decode(file_get_contents($this->getCreditsUsedLogFile()), true) ?: [];

        if (!array_key_exists($name, $json)) {
            return 0;
        } else {
            return $json[$name];
        }
    }

    public function trackQuotaAndCreditLimits($name, Closure $execute)
    {
        $creditsUsed = $this->getCreditsUsed($name);

        // Check credits used
        if ($creditsUsed >= $this->getCreditLimit($name)) {
            throw new Exception("You do not have enough credits left"); // or Callback here
        }

        // Check quota allowance
        $quotaTracker = new QuotaTracker($this->getQuotaTrackerLogFile($name), $this->getQuotaSettings());
        if (!$quotaTracker->check()) {
            throw new Exception("Quota limits reached"); // or Callback here
        }

        // Okie, execute
        $execute();

        // Update credits used and tracker's log
        $quotaTracker->count();
        $this->countCreditUsed();
    }
}
