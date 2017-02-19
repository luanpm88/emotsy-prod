<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Acelle\Library\QuotaTrackerFile;
use Carbon\Carbon;

class QuotaTrackerMultiProcessTest extends TestCase
{
    /**
     * Test if the quota tracking system itself is correct
     *
     * @return void
     */
    public function test01()
    {
        $file = '/tmp/quotatest';
        $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
        $tracker->renew([]);
        $this->assertTrue(empty($tracker->getSeries()));
        
        $parentPid = getmypid();
        $children = [];
        for ($i = 1; $i <= 3; ++$i) {
            $pid = pcntl_fork();
            
            // for child process only
            if (!$pid) {
                sleep(1);
                
                $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
                
                $result = $this->nTimes(300, $tracker, function($tracker) {
                    return $tracker->add(Carbon::now());
                });
                
                exit($i + 1);
                // end child process
            } else {
                $children[] = $pid;
            }
        }

        // wait for child processes to finish
        foreach($children as $child) {
            $pid = pcntl_wait($status);
            $this->assertTrue(pcntl_wifexited($status));
        }
        
        $this->assertTrue(sizeof($tracker->getSeries()) == 900);
    }
    
    /**
     * Simulate an activity every second
     *
     * @return void
     */
    private function nTimes($try, $tracker, $func) {
        $success = true;
        for($i = 0; $i < $try; $i++) {
            $success = $func($tracker);
            if (!$success) {
                return false;
            }
            //sleep(1);
        }
        return $success;
    }
}
