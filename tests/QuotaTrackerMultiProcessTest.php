<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Acelle\Library\QuotaTrackerFile;
use Acelle\Model\User;
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
     * Test User quota usage
     */
    public function testUserQuotaUsage()
    {
        // setup assumptions
        $user = $this->getMockBuilder(User::class)
                     ->setMethods(['getQuotaIntervalString', 'getSendingQuota', 'renewQuotaTracker', 'getSendingQuotaLockFile'])
                     ->disableOriginalConstructor()
                     ->disableOriginalClone()
                     ->disableArgumentCloning()
                     ->disallowMockingUnknownTypes()
                     ->getMock();
        
        // assume that REDIS is not enabled (then QuotaTrackerStd is used)
        // and user quota is: 3 emails every 1 minute
        //\Config::shouldReceive('get')
        //            ->once()
        //            ->with('app.redis_enabled')
        //            ->andReturn(false);
        
        $user->method('getQuotaIntervalString')->willReturn('1 minute');
        $user->method('getSendingQuotaLockFile')->willReturn(storage_path("app/user/quota/dummy"));
        $user->method('getSendingQuota')->willReturn('3');
        
        // TEST IF QUOTA IS CORRECTLY ENFORCED
        // no usage yet
        $this->assertFalse($user->overQuota());

        // use 3 slots out of 3 slots allowed -> ok
        $this->assertTrue($user->countUsage());
        $this->assertTrue($user->countUsage());
        $this->assertTrue($user->countUsage());
        
        // use extra slot -> failed
        $this->assertTrue($user->overQuota());
        $this->assertFalse($user->countUsage());

        sleep(61);
        // more than 1 minute has passed, now OKIE
        $this->assertFalse($user->overQuota());
        
        // TEST IF THE TIME SERIES IS CORRECTLY RECORDED
        $t1 = Carbon::now();
        $user->countUsage($t1);
        sleep(20);
        $t2 = Carbon::now();
        $user->countUsage($t2);
        
        // now $t1 is the first point of the time series
        echo "\nhello\n";
        $this->assertTrue($user->getQuotaTracker()->getSeries()[0] == $t1->timestamp);

        sleep(50);
        // now $t2 becomes the first point of the time series
        $this->assertTrue($user->getQuotaTracker()->getSeries()[0] == $t2->timestamp);
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
