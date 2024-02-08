<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;
use Acelle\Library\InMemoryRateTracker;

class InMemoryRateTrackerTest extends TestCase
{
    function test_in_memory_rate_tracker_just_works()
    {
        $this->assertEquals(1, 1);

        $key = 'test-in-memory-rate-tracker';
        $tracker = new InMemoryRateTracker($key);

        $now = Carbon::now();
        $oneMinuteAgo = $now->clone()->add('1 minute ago');

        // 4 times
        $tracker->count($oneMinuteAgo);
        $tracker->count($oneMinuteAgo);
        $tracker->count($now);
        $tracker->count($now);

        /*
         * After 4 counts, the lock file should look like this
         *
         * 202307300013:2
         * 202307300014:2
         *
         */

        $used = $tracker->getCreditsUsed($oneMinuteAgo, $now);
        $this->assertEquals($used, 4);

        // Rollback 1
        $tracker->rollback();

        /*
         * After rollback 1, the lock file should look like this
         *
         * 202307300013:2
         * 202307300014:1
         *
         */

        $used = $tracker->getCreditsUsed($oneMinuteAgo, $now);
        $this->assertEquals($used, 3);

        // Rollback 2
        $tracker->rollback();

        /*
         * After rollback 2, the lock file should look like this
         *
         * 202307300013:2
         *
         */

        $used = $tracker->getCreditsUsed($oneMinuteAgo, $now);
        $this->assertEquals($used, 2);

        sleep(5);

        $used = $tracker->getCreditsUsed(now()->add('1 minute ago'), $now);
        $this->assertEquals($used, 2);

        $used = $tracker->getCreditsUsed(now()->add('6 seconds ago'), $now);
        $this->assertEquals($used, 0);
    }
}