<?php

namespace Acelle\Library;

use Exception;
use Closure;
use Carbon\Carbon;
use File;
use Acelle\Library\Contracts\HasQuota as HasQuotaInterface;

class QuotaManager
{
	const QUOTA_ZERO = 0;
    const QUOTA_UNLIMITED = -1;

	protected $subject;
	protected $name;
	protected $quotaTracker;

	public function __construct(HasQuotaInterface $subject, string $name)
	{
		$this->subject = $subject;
		$this->name = $name;
	}

	public static function with(HasQuotaInterface $subject, string $name) {
		$wrapper = new static($subject, $name);
		return $wrapper;
	}

	public function getCreditsStorageFile()
	{
		// store remaining credits
		return storage_path("app/quota/credits-{$this->subject->getUid()}");
	}

	public function getCreditsLogFile()
	{
		return storage_path("app/quota/credits-log-{$this->name}-{$this->subject->getUid()}");
	}

	public function cleanup()
	{
		$files = [
			$this->getCreditsStorageFile(),
			$this->getCreditsLogFile(),
		];

		foreach($files as $file) {
			if (File::exists($file)) {
				File::delete($file);
			}
		}
	}

	public function getQuotaTracker()
    {
    	if (is_null($this->quotaTracker)) {
    		$this->quotaTracker = new QuotaTracker($this->getCreditsLogFile());
    	}

        return $this->quotaTracker;
    }

    public function getCreditsStorageJson()
    {
        $filepath = $this->getCreditsStorageFile();
        if (!file_exists($filepath)) {
            \Acelle\Helpers\ptouch($filepath);
        }

        $json = json_decode(file_get_contents($filepath), true) ?: [];
        return $json;
    }

    private function updateCreditsLog($json) {
        $filepath = $this->getCreditsStorageFile();
        file_put_contents($filepath, json_encode($json));
    }

    public function getRemainingCredits() {
        $json = $this->getCreditsStorageJson();
        if (!array_key_exists($this->name, $json)) {
            throw new Exception(sprintf('Credits limit for object %s#%s is not initialized yet. Initiate it with setCredits or addCredits first', get_class($this->subject), $this->subject->uid));
        }

        // This is the remaining credits
        return $json[$this->name];
    }

    public function addCredits($count)
    {
        $json = $this->getCreditsStorageJson();

        if (!array_key_exists($this->name, $json)) {
            $json[$this->name] = $count;
        } else {
            $json[$this->name] = $json[$this->name] + $count;
        }

        $this->updateCreditsLog($json);
    }

    public function setCredits($count)
    {
        $json = $this->getCreditsStorageJson();
        $json[$this->name] = $count;
        $this->updateCreditsLog($json);
    }

    // MAIN
    public function updateRemainingCredits()
    {
        $json = $this->getCreditsStorageJson();
        if (!array_key_exists($this->name, $json)) {
            throw new Exception('No credits information available, initialize it first by setCredits() or addCredits()');
        }

        $remaining = $json[$this->name];

        if ($remaining === self::QUOTA_UNLIMITED) {
            // QUOTA_UNLIMITED (-1) also means "unlimited"
            return;
        }

        if ($remaining === self::QUOTA_ZERO) {
            // QUOTA_ZERO (0) means "limit reached"
            throw new Exception("Credits remaining is already 0");
        }

        // Deduct remaining credits
        $json[$this->name] = $remaining - 1;
        $this->updateCreditsLog($json);
    }

    public function getCreditsUsed(Carbon $from = null, Carbon $to = null)
    {
        return $this->getQuotaTracker()->getCreditsUsed($from, $to);
    }

    public function withQuota(Closure $execute)
	{
	    // Check available credits and quota
	    // Count if passed, throw exception otherwise
	    $limits = $this->subject->getQuotaSettings($this->name);

	    // Check quota allowance
	    $now = Carbon::now();

	    // Count quota use
	    $this->getQuotaTracker()->count($now, $limits, function() {
	        // Take advantage of the lock to count remaining credits
	        $this->updateRemainingCredits();
	    });

	    // Okie, execute
	    $execute();
	}

	public static function count(HasQuotaInterface $subject, string $name, Closure $execute)
	{
		$manager = static::with($subject, $name);
		$manager->withQuota($execute);
	}
}