<?php

namespace Acelle\Library;
use Carbon\Carbon;

class License
{
	protected $license;
	protected $type;
	protected $supportedUntil;
	protected $status;
	protected $buyer;

	public const STATUS_ACTIVE = 'active';
	public const STATUS_EXPIRED = 'expired';

	function __construct($license, $type, $status, $supportedUntil, $buyer = null)
	{
		$this->license = $license;
		$this->type = $type;
		$this->supportedUntil = Carbon::parse($supportedUntil);
		$this->status = $status;
		$this->buyer = $buyer;
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

    public function getBuyer()
    {
    	return $this->buyer;
    }
}
