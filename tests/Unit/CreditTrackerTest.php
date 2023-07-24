<?php

use Acelle\Library\CreditTracker;
use Acelle\Library\Exception\OutOfCredits;

test('Credit tracker just works', function () {
    $file = '/tmp/test-rate-'.uniqid();
    $tracker = CreditTracker::load($file, $createFile = true);

    expect($tracker->getCredits())->toBe(CreditTracker::ZERO);

    $tracker->setCredits(2);
    expect($tracker->getCredits())->toBe(2);

    // Count to deduct credit
    $tracker->count();
    expect($tracker->getCredits())->toBe(1);

    // Count to deduct credit
    $tracker->count();
    expect($tracker->getCredits())->toBe(0);
});

test('Credit tracker should throw OutOfCredits', function () {
    $file = '/tmp/test-rate-'.uniqid();
    $tracker = CreditTracker::load($file, $createFile = true);
    $tracker->setCredits(1);   // credits = 1
    $tracker->count();         // credits = 0

    // Since credit is already ZERO, another count() shall throw an exception
    $tracker->count();
})->throws(OutOfCredits::class);


test('Credit tracker rollback just works', function () {
    $file = '/tmp/test-rate-'.uniqid();
    $tracker = CreditTracker::load($file, $createFile = true);
    $tracker->setCredits(1);   // credits = 1
    $tracker->count();         // credits = 0

    $tracker->rollback();      // credits expected to be 1
    expect($tracker->getCredits())->toBe(1);

    $tracker->rollback();
    expect($tracker->getCredits())->toBe(2);

});
