<?php

namespace Tests\Unit;

use Tests\TestCase;

use Acelle\Library\QuotaTracker;
use Acelle\Model\SendingServer;
use Exception;
use File;
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

        return $server;
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
        $this->assertTrue($server->getRemainingCredits('send') == $credits - $use);
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
            $this->assertTrue($i == 5); // failed at the first try after limit is reached
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
            $this->assertTrue($i == $credits + 1); // failed at the first try after limit is reached
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
            $this->assertTrue($i == $use + 1); // failed at the first try after limit is reached
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
            $this->assertTrue($i == $use + 1); // failed at the first try after limit is reached
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
}
