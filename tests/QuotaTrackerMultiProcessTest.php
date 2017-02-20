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
    public function testRaceCondition()
    {
        $file = '/tmp/quotatest';
        $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
        $tracker->getExclusiveLock(function(&$tracker) {
            // the getSeries() here is to ensure that lock is not set again if the current process already got it
            // for the sake of code coverage
            $tracker->getSeries();
            $tracker->reset();
        });

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
     * Test the QuotaTracker::reset() function in case of transaction safe
     *
     * @return void
     */
    public function testResetWithTransactionSafe()
    {
        $file = '/tmp/quotatest';
        $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
        $tracker->getExclusiveLock(function(&$tracker) {
            $tracker->reset();
        });

        $this->assertTrue(empty($tracker->getSeries()));

        $parentPid = getmypid();
        $children = [];
        for ($i = 1; $i <= 4; ++$i) {
            $pid = pcntl_fork();
            
            // for child process only
            if (!$pid) {
                if ($i > 2) {
                    $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
                    $result = $this->nTimes(500, $tracker, function($tracker) {
                        return $tracker->add(Carbon::now());
                    });
                    
                    exit($i + 1);
                } else {
                    $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
                    $tracker->getExclusiveLock(function(&$tracker) {
                        $s = $tracker->getSeries();
                        sleep(1);
                        $tracker->reset($s);
                    });
                }
                
                exit(0);
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
        
        $this->assertTrue(sizeof($tracker->getSeries()) == 1000);
    }
    
    /**
     * Test the QuotaTracker::reset() function without transaction safe
     *
     * @return void
     */
    public function testResetWithoutTransactionSafe()
    {
        $file = '/tmp/quotatest';
        $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
        $tracker->getExclusiveLock(function(&$tracker) {
            $tracker->reset();
        });

        $this->assertTrue(empty($tracker->getSeries()));

        $parentPid = getmypid();
        $children = [];
        for ($i = 1; $i <= 4; ++$i) {
            $pid = pcntl_fork();
            
            // for child process only
            if (!$pid) {
                if ($i > 2) {
                    $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
                    $result = $this->nTimes(500, $tracker, function($tracker) {
                        return $tracker->add(Carbon::now());
                    });
                    
                    exit($i + 1);
                } else {
                    $tracker = new QuotaTrackerFile($file, '1 hour', 100000);
                    $s = $tracker->getSeries();  // not transaction safe: <--- other processes may jump into this point
                    sleep(1);                    // not transaction safe: <--- other processes may jump into this point
                    $tracker->getExclusiveLock(function(&$tracker) use ($s) {
                        $tracker->reset($s);
                    });
                }
                
                exit(0);
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
        
        $this->assertFalse(sizeof($tracker->getSeries()) == 1000);
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
