<?php 

namespace Acelle\Library;

class TransactionVerificationResult
{
	const RESULT_DONE = 'done';
	const RESULT_FAILED = 'failed';
	const RESULT_STILL_PENDING = 'still-pending';

    // chỉ dành cho Stripe
	const RESULT_VERIFICATION_NOT_NEEDED = 'not-supported';

	public $result;
	public $error;

	public function __construct($result, $error = null)
	{
		$this->result = $result;
		$this->error = $result;
	}

	public function isDone() {
		// normally run invoice.fulfill() after that
		return $this->result == self::RESULT_DONE;
	}

	public function isFailed() {
		// run invoice.payfailed() after that
		return $this->result == self::RESULT_FAILED;
	}

	public function isStillPending() {
		// Normally for services that immediately returns a result already
		// 
		return $this->result == self::RESULT_STILL_PENDING;
	}

	public function isVerificationNotNeeded()
	{
		return $this->result = self::RESULT_VERIFICATION_NOT_NEEDED;
	}
}