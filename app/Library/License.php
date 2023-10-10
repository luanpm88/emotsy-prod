<?php

namespace Acelle\Library;
use Carbon\Carbon;

class License
{
	protected $license;
	protected $type;
	protected $supportedUntil;
	protected $status;

	public const STATUS_ACTIVE = 'active';
	public const STATUS_EXPIRED = 'expired';

	function __construct($license, $type, $status, $supportedUntil)
	{
		$this->license = $license;
		$this->type = $type;
		$this->supportedUntil = Carbon::parse($supportedUntil);
		$this->status = $status;
	}

	public function isActive()
	{
            return $this->status == self::STATUS_ACTIVE;
	}

        public function isExpired()
        {
            return $this->status == self::STATUS_EXPIRED;
        }

	public function getLicenseNumber()
	{
            return $this->license;
	}

	public function getType()
	{
            return $this->type;
	}

        public function getSupportedUntil()
        {
            return $this->supportedUntil;
        }
}
