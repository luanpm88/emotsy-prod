<?php

namespace Acelle\Library;

use Acelle\Library\Exception\OutOfCredits;

class CreditTracker
{
	protected $filepath;
	protected $limit;

	public const ZERO = 0;

	// Using CreditTracker::load('file') makes more sense than
	public static function load($filepath, bool $createFileIfNotExists = false)
	{
		$tracker = new self($filepath, $createFileIfNotExists);
		return $tracker;
	}

	private function __construct($filepath, bool $createFileIfNotExists)
	{
		$this->filepath = $filepath;

		if ($createFileIfNotExists && !file_exists($this->filepath)) {
			$this->createFile();
		}
	}

	public function createFile()
	{
		$file = fopen($this->filepath, 'w');
		fclose($file);
	}

	public function getCredits()
	{
		$credits = file_get_contents($this->filepath);
		if (empty(trim($credits))) {
			return self::ZERO;
		}

		return (int)$credits;
	}

	public function test()
	{
		if ($this->getCredits() == self::ZERO) {
			throw new OutOfCredits('Credits exceeded');
		}
	}

	public function count()
	{
		$this->test();

		$remainingCredits = $this->getCredits();
		$remainingCredits -= 1;
		$remainingCredits = "{$remainingCredits}"; // cast to string

		file_put_contents($this->filepath, $remainingCredits);
	}

	public function setCredits($amount)
	{
		file_put_contents($this->filepath, (string)$amount);
	}
}