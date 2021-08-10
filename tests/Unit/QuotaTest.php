<?php

namespace Tests\Unit;

use Tests\TestCase;

use Acelle\Library\QuotaTracker;
use Acelle\Model\SendingServer;
use Acelle\Model\Subscription;
use Exception;
use File;
use Mockery;

class QuotaTest extends TestCase
{
    public function initServer()
    {
        $server = SendingServer::first();
        $server->setCredits('send', 100);

        $quotaLogFile = $server->getQuotaLogFile('send');
        if (File::exists($quotaLogFile)) {
            File::delete($quotaLogFile);
        }

        $creditsLogFile = $server->getCreditsLogFile();
        if (File::exists($creditsLogFile)) {
            File::delete($creditsLogFile);
        }

        return $server;
    }

    public function initSubscription()
    {
        $subscription = Subscription::first();
        $subscription->setCredits('send', 100);

        $quotaLogFile = $subscription->getQuotaLogFile('send');
        if (File::exists($quotaLogFile)) {
            File::delete($quotaLogFile);
        }

        $creditsLogFile = $subscription->getCreditsLogFile();
        if (File::exists($creditsLogFile)) {
            File::delete($creditsLogFile);
        }

        return $subscription;
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_just_works()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 100);
        $use  = rand(3,10);
        $server->setQuotaSettings($limit = 10000000, $unit = 1, $base = 'minute');

        for($i = 1; $i <= $use; $i++) {
            $server->withQuota('send', function() {
                // echo "Credits is deducted\n";
            });

            echo "Use {$i}: remaining {$server->getRemainingCredits('send')}\n";
        }

        echo "Result\nUsed credits {$use}, Remaining is {$server->getRemainingCredits('send')}\n";
        $this->assertEquals($server->getRemainingCredits('send'), $credits - $use);
        $this->assertEquals($server->getCreditsUsed('send'), $use);
    }

    public function test_speed_limit_exceeded()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 100);
        $limitPerMinute = 4;
        $use = 10;

        $server->setQuotaSettings($limitPerMinute, $unit = 1, $base = 'minute');

        $this->expectException(Exception::class);

        try {
            for($i = 1; $i <= $use; $i++) {
                $server->withQuota('send', function() {
                    // echo "Credits is deducted\n";
                });

                echo "Use {$i}: remaining {$server->getRemainingCredits('send')}\n";
            }
        } finally {
            $this->assertEquals($i, 5); // failed at the first try after limit is reached
        }
    }

    public function test_credit_exceeded()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 5);
        $use = 1000;

        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 1, $base = 'minute');
        $this->expectException(Exception::class);

        try {
            for($i = 1; $i <= $use; $i++) {
                $server->withQuota('send', function() {
                    // echo "Credits is deducted\n";
                });

                echo "Use {$i}: remaining {$server->getRemainingCredits('send')}\n";
            }
        } finally {
            $this->assertEquals($i, $credits + 1); // failed at the first try after limit is reached
        }
    }

    public function test_speed_limit_reached()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 100);
        $limitPerMinute = 4;
        $use = 4;

        $server->setQuotaSettings($limitPerMinute, $unit = 1, $base = 'minute');

        try {
            for($i = 1; $i <= $use; $i++) {
                $server->withQuota('send', function() {
                    // echo "Credits is deducted\n";
                });
            }
        } finally {
            $this->assertEquals($i, $use + 1); // failed at the first try after limit is reached
        }
    }

    public function test_credit_reached()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 5);
        $use = 5;

        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 1, $base = 'minute');

        try {
            for($i = 1; $i <= $use; $i++) {
                $server->withQuota('send', function() {
                    // echo "Credits is deducted\n";
                });
            }
        } finally {
            $this->assertEquals($i, $use + 1); // failed at the first try after limit is reached
        }
    }

    public function test_zero_credits()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 0);
        $server->setQuotaSettings($limitsPerMinute = 10000, $unit = 1, $base = 'minute');
        $this->expectException(Exception::class);
        $server->withQuota('send', function() {
            // echo "Credits is deducted\n";
        });
    }

    public function test_zero_credits_per_minute()
    {
        $server = $this->initServer();
        $server->setCredits('send', $credits = 10000);
        $server->setQuotaSettings($limitsPerMinute = 0, $unit = 1, $base = 'minute');
        $this->expectException(Exception::class);
        $server->withQuota('send', function() {
            // echo "Credits is deducted\n";
        });
    }

    public function test_subscription_just_works()
    {
        $subscription = $this->initSubscription();
        $subscription->setCredits('send', 10000);
        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([
            [
                'name' => "Plan's sending limit",
                'limit' => 10,
                'period_base' => 'day',
                'period_value' => 1,
            ]

        ]);

        // Assign the mocked plan
        $subscription->plan = $plan;

        // Max allowed + 1
        $use  = 11;

        $this->expectException(Exception::class);

        try {
            for($i = 1; $i <= $use; $i++) {
                $subscription->withQuota('send', function() {
                    // echo "Credits is deducted\n";
                });

                echo "Use {$i}: remaining {$subscription->getRemainingCredits('send')}\n";
            }
        } finally {
            $this->assertEquals($i, 11); // failed at the first try after limit is reached
        }
    }

    public function test_subscription_just_with_unlimited_sending_speed()
    {
        $subscription = $this->initSubscription();
        $subscription->setCredits('send', 100);
        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([]);

        // Assign the mocked plan
        $subscription->plan = $plan;

        // Max allowed + 1
        $use  = 101;

        // Quota is null, so it just runs until sending credits == 0
        // Expect to see an exception at 101th use!
        $this->expectException(Exception::class);

        try {
            for($i = 1; $i <= $use; $i++) {
                $subscription->withQuota('send', function() {
                    // echo "Credits is deducted\n";
                });

                echo "Use {$i}: remaining {$subscription->getRemainingCredits('send')}\n";
            }
        } finally {
            $this->assertTrue($i == 101 && 0 === $subscription->getRemainingCredits('send')); // failed at the first try after limit is reached
        }
    }

    public function test_subscription_just_with_unlimited_sending_credits()
    {
        $subscription = $this->initSubscription();
        $subscription->setCredits('send', -1);
        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([
            [
                'name' => "Plan's sending limit",
                'limit' => 999999,
                'period_base' => 'month',
                'period_value' => 1,
            ]

        ]);

        // Assign the mocked plan
        $subscription->plan = $plan;
        $tests = 100;
        try {
            for($i = 1; $i <= $tests; $i++) {
                $subscription->withQuota('send', function() {
                    // echo "Credits is deducted\n";
                });

                echo "Use {$i}: remaining {$subscription->getRemainingCredits('send')}\n";
            }
        } finally {
            // failed at the first try after limit is reached
            $this->assertEquals(Subscription::QUOTA_UNLIMITED, $subscription->getRemainingCredits('send'));
            $this->assertEquals($subscription->getCreditsUsed('send'), $tests);
        }
    }

    public function test_subscription_with_null_credits_used_information()
    {
        $subscription = $this->initSubscription();
        $creditsLogFile = $subscription->getCreditsLogFile();

        // Remove the (used) credits log file
        if (File::exists($creditsLogFile)) {
            File::delete($creditsLogFile);
        }

        $plan = Mockery::mock('plan');
        $plan->shouldReceive('getQuotaSettings')->andReturn([
            [
                'name' => "Plan's sending limit",
                'limit' => -1,
                'period_base' => 'month',
                'period_value' => 1,
            ]

        ]);

        // Assign the mocked plan
        $subscription->plan = $plan;

        $this->expectException(Exception::class);
        $subscription->withQuota('send', function() {
            // echo "Credits is deducted\n";
        });
    }
}
