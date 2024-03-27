<?php

namespace Acelle\Library;

use Carbon\Carbon;
use Exception;
use Acelle\Library\Exception\RateLimitExceeded;
use Closure;
use Illuminate\Support\Facades\Cache;

/*
 * Log every credit used to a log file, group by $mode, which could be 'minute' (default), hour, day, month...
 * For example, a log file grouped by minute may look like this ( lines of YYYYMMDDHHMM:COUNT format)
 *
 * 202307230913:1200
 * 202307230914:1250
 * 202307230914:1052
 * 202307230930:1178
 *
 * Group by 'minute' supports minute granularity limits (emails per 1 minute)
 * Group by 'hour' supports hour granularity limits (emails per 1 hour), but does not support minute (lower than 1 hour) granularity limits
 *
 */

class DynamicRateTracker
{
    protected $resourceKey;
    protected $reservedUntilKey;
    protected $reservedPath;
    protected $limits;

    protected $seperator = ':';

    public function __construct(string $resourceKey, $limits = [], $testMode = false) // RateLimit class
    {
        if (empty($limits)) {
            throw new Exception('RateLimit $limits must not be empty');
        }

        $this->resourceKey = $resourceKey;
        $this->reservedUntilKey = $resourceKey."-reserved-until";

        if ($testMode) {
            // for testing multiple client on the same PC
            $this->reservedPath = "/tmp/dynamic-rate-tracker-test-".rand(1,100000);
        } else {
            $this->reservedPath = "/tmp/dynamic-rate-tracker-".md5($this->resourceKey);
        }

        $this->limits = $limits;

        if (!file_exists($this->reservedPath)) {
            touch($this->reservedPath);
        }
    }

    public function getReservedPath()
    {
        return $this->reservedPath;
    }

    // Reverse of count()
    // @deprecated: rollback is not needed as even a failed operation is also counted in rate limits
    public function rollback()
    {
        throw new Exception('Rollback not supported with DynamicRateTracker');
    }

    public function getRateLimits()
    {
        return $this->limits;
    }

    // Example of $period: "24 hours", "1 week"
    // i.e. Clean up credit tracking logs that are older than "24 hours", "1 week"
    public function cleanup(string $period = null)
    {
        // no cleanup needed
    }

    private function updateReservedCredits(Carbon $until, $credits)
    {
        file_put_contents($this->reservedPath, "{$until->timestamp}{$this->seperator}{$credits}");
    }

    public function testAndCount(Carbon $now, $fopen)
    {
        list($until, $credits) = $this->parseReservedCredits($fopen);

        if ($until && $until->gte($now) && $credits > RateLimit::ZERO) {
            $this->updateReservedCredits($until, $credits - 1);
            return true;
        } else {
            return false;
        }
    }

    // Test and throw exception, do not count()
    // Used by DelayedJob
    public function test(Carbon $now = null)
    {
        $now = $now ?: Carbon::now();
        list($until, $credits) = $this->parseReservedCredits();

        if ($until && $until->gte($now) && $credits > RateLimit::ZERO) {
            return true;
        } else {
            if ($this->isReservedByOthers($now)) {
                // no local reserved
                // and there is a reserved-until key (by other)
                throw new RateLimitExceeded('Rate limit exceeded (reserved by others)');
            }
        }
    }

    public function count(Carbon $now = null)
    {
        $now = $now ?: Carbon::now();
        $available = false;

        // Step 1: check if there is a local reservation
        $lock = new Lockable($this->reservedPath);
        $lock->getExclusiveLock(function ($fopen) use ($now, &$available) {
            if ($this->testAndCount($now, $fopen)) {
                $available = true;
            }
        });

        if ($available) {
            // If there is a valid local reservation, then move forward
            // without having to check the remote tracker
            return;
        }

        // If not available locally, check the remote
        with_cache_lock($this->resourceKey, function () use ($now) {
            if ($this->isReservedByOthers($now)) {
                // no local reserved
                // and there is a reserved-until key (by other)
                throw new RateLimitExceeded('Rate limit exceeded (reserved by others)');
            }

            // Okie, so ready
            $until = $now->add($this->getShortestLimitPeriod());
            $credits = $this->getMinCredits();

            // reserve credits for local & remote
            // also count 1 credit used
            $this->reserve($until, $credits - 1);

        }, $timeout = 15);
    }

    public function isReservedByOthers(Carbon $now)
    {
        $reservedUntil = (int)Cache::get($this->reservedUntilKey);

        if (!is_null($reservedUntil) && (new Carbon($reservedUntil))->gte($now)) {
            return true;
        } else {
            return false;
        }
    }

    private function reserve(Carbon $until, $credits)
    {
        // Set local
        $this->updateReservedCredits($until, $credits);

        // Set remote reservef flag
        Cache::put($this->reservedUntilKey, $until->timestamp);
    }

    public function parseReservedCredits($fopen = null)
    {
        if (is_null($fopen)) {
            $fopen = fopen($this->getReservedPath(), 'r');
        }

        rewind($fopen);
        $contents = fgets($fopen);
        if (empty($contents)) {
            return [null, null];
        }

        list($until, $credits) = explode($this->seperator, $contents);

        return [new Carbon((int)$until), (int)$credits];
    }

    public function getShortestLimitPeriod()
    {
        $shortest = null;
        $now = now();
        foreach($this->limits as $limit) {
            $d = $now->copy()->add($limit->getPeriod());
            if (is_null($shortest) || $now->copy()->add($shortest->getPeriod())->gte($d)) {
                $shortest = $limit;
            }
        }

        return $shortest->getPeriod();
    }

    public function getMinCredits()
    {
        $min = null;
        foreach($this->limits as $limit) {
            if (is_null($min) || $limit->getAmount() < $min) {
                $min = $limit->getAmount();
            }
        }

        return $min;
    }

    public function clearReservation()
    {
        file_put_contents($this->getReservedPath(), '');
        Cache::forget($this->reservedUntilKey);
    }
}
