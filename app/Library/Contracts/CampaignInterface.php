<?php

namespace Acelle\Library\Contracts;

interface CampaignInterface
{
	// Operation
	public function run();
	public function execute();
	public function pause();
	public function resume();

	// Set status
	public function setDone();
	public function setQueuing();
	public function setQueued();
	public function setSending();
	public function setScheduled();
	public function setPaused();
	public function setError($error = null);

	// Check status
	public function isQueued();
	public function isSending();
	public function isDone();
	public function isPaused();
	public function isError();

	// MISC
	public function extractErrorMessage();
	public static function checkAndExecuteScheduledCampaigns();
}