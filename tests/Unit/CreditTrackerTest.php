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

	expect($tracker->getCredits())->toBe(CreditTracker::ZERO);

	$tracker->setCredits(1);
	expect($tracker->getCredits())->toBe(1);

	// Count to deduct credit to ZERO
	$tracker->count(); // ==> 0

	// Since credit is already ZERO, another count() shall throw an exception
	$tracker->count();
})->throws(OutOfCredits::class);