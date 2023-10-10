<?php

namespace Acelle\Library;

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
		$this->supportedUntil = $supportedUntil;
		$this->status = $status;
	}

	public function isActive()
	{
		return $this->status == self::STATUS_ACTIVE;
	}

	public function getLicenseNumber()
	{
		return $this->license;
	}

	public function getType()
	{
		return $this->type;
	}
}