<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;
use Acelle\Library\DynamicRateTracker;
use Acelle\Library\RateLimit;
use Acelle\Library\Exception\RateLimitExceeded;
use function \Acelle\Helpers\execute_with_limits;

class DynamicRateTrackerTest extends TestCase
{
	function test_just_works()
	{
	    $key = 'test-dynamic-rate-tracker';
	    $limits = [
	        new RateLimit(10, 1, 'minute', '2 per minute'),
	        new RateLimit(200, 12, 'hour', '5 per 24 hours (per day)'),
	        new RateLimit(4, 1, 'minute', '5 per 24 hours (per day)'),
	        new RateLimit(5, 1, 'day', '5 per 24 hours (per day)'),
	    ];

	    $tracker = new DynamicRateTracker($key, $limits);

	    // clean up reserved file
	    $tracker->clearReservation();

	    // So the selected limit for reserving is: "4 per 1 minute"
	    $this->assertEquals($tracker->getShortestLimitPeriod(), '1 minute');
	    $this->assertEquals($tracker->getMinCredits(), 4);

	    $tracker->count();

	    list($until, $credits) = $tracker->parseReservedCredits();
	    $this->assertEquals($credits, 3);

	    $tracker->count();
	    $tracker->count();
	    $tracker->count();

	    list($until, $credits) = $tracker->parseReservedCredits();
	    $this->assertEquals($credits, 0);
	}

	function test_with_exception()
	{
		$key = 'test-dynamic-rate-tracker';
	    $limits = [
	        new RateLimit(3, 1, 'minute', '3 per minute'),
	        new RateLimit(200, 12, 'hour', '5 per 24 hours (per day)'),
	    ];

	    $tracker = new DynamicRateTracker($key, $limits);

	    // clean up reserved file
	    $tracker->clearReservation();

	    // So the selected limit for reserving is: "4 per 1 minute"
	    $this->assertEquals($tracker->getShortestLimitPeriod(), '1 minute');
	    $this->assertEquals($tracker->getMinCredits(), 3);

	    $tracker->count();
	    $tracker->count();
	    $tracker->count();

	    list($until, $credits) = $tracker->parseReservedCredits();
	    $this->assertEquals($credits, 0);

	    $this->expectException(RateLimitExceeded::class);
	    $tracker->count();
	}

	function test_with_no_limits_at_all()
	{
		$key = 'test-dynamic-rate-tracker';
	    $tracker = new DynamicRateTracker($key, $limits = []);

	    // clean up reserved file
	    $tracker->clearReservation();

	    // So the selected limit for reserving is: "4 per 1 minute"
	    $this->assertNull($tracker->getMinCredits());

	    $tracker->count();
	    $tracker->count();
	    $tracker->count();

	    $tracker->test();
	}

	function test_with_multi_process()
	{
		$key = 'test-dynamic-rate-tracker';
	    $limits = [
	        new RateLimit(2, 5, 'seconds', '2 per 30 secs'),
	        new RateLimit(200, 12, 'hour', '5 per 24 hours (per day)'),
	    ];

	    $tracker = new DynamicRateTracker($key, $limits);

	    // !!!!!!!!!!!!!!! IMPORTANT - do not clear here
	    // $tracker->clearReservation();

	    // So the selected limit for reserving is: "4 per 1 minute"
	    $this->assertEquals($tracker->getShortestLimitPeriod(), '5 seconds');
	    $this->assertEquals($tracker->getMinCredits(), 2);

	    // Only test it manually, by running two consoles separately with the following command
	    //
	    //     ./vendor/bin/pest --filter=test_with_multi_process
	    //
	    // And monitor the outcome:
	    //
	    //    tail -f /tmp/test-multi-process
	    //

	    return;

	    while (true) {
	    	try {
	    		execute_with_limits($rateTrackers = [$tracker], $creditsTrackers = [], function() {
		    		// ONLY WRITE 2 lines (in total) every 30 seconds
	    			$now = now()->toString();
		    		$message = "{$now}: Added by ".getmypid()."\n";
			    	$f = fopen("/tmp/test-multi-process", "a") or die("Unable to open file!");
					fwrite($f, $message);
					fclose($f);
					echo " - Done\n";
		    	});
	    	} catch (RateLimitExceeded $ex) {
	    		// no problem, try again
	    		echo " - Rate exceeded\n";
	    		sleep(rand(1,5));
	    	}
	    }
	}
}
