<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Acelle\Library\QuotaTracker;
use Carbon\Carbon;

class QuotaTrackerTest extends TestCase
{
    /**
     * Test if the quota tracking system itself is correct
     *
     * @return void
     */
    public function testOverQuota()
    {
        /**
         * Suppose the quota setting is: {6 emails, every 5 seconds}
         * Then we test with: send an email every 1 second and and expect it will NOT to exceed the quota
         */
        $tracker = new QuotaTracker(5, 6);
        $result = $this->nTimes(10, $tracker, function($tracker) {
            return $tracker->add(Carbon::now()->timestamp);
        });
        $this->assertTrue($result);

        /**
         * Suppose the quota setting is: {5 emails, every 4 seconds}
         * Then we test with: send an email every 1 second and and expect it will exceed the quota
         */
        $tracker = new QuotaTracker(5, 4);
        $result = $this->nTimes(5, $tracker, function($tracker) {
            return $tracker->add(Carbon::now()->timestamp);
        });
        $this->assertFalse($result);

        /**
         * Suppose the quota setting is: {0 emails, every 1 hours}
         * Then we test with: send an email every 1 second and and expect it to fail immediately at first try
         */
        $tracker = new QuotaTracker(3600, 0);
        $result = $this->nTimes(1, $tracker, function($tracker) {
            return $tracker->add(Carbon::now()->timestamp);
        });
        $this->assertFalse($result);
    }
    
    /**
     * Simulate an activity every second
     *
     * @return void
     */
    private function nTimes($try, $tracker, $func) {
        $success = true;
        for($i = 0; $i < $try; $i++) {
            echo "$i";
            $success = $func($tracker);
            if (!$success) {
                return false;
            }
            sleep(1);
        }
        return $success;
    }
}
