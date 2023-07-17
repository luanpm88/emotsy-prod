<?php

namespace Acelle\Library\Contracts;

interface CampaignInterface
{
	// Operation
	public function launch();
	public function execute();
	public function pause();
	public function resume();

	// Set status
	public function setDone();
	public function setQueued();
	public function setSending();
	public function setQueuing();
	public function setPaused();
	public function setScheduled();
	public function setError($error = null);

	// Check status
	public function isSending();
	public function isDone();
	public function isQueued();
	public function isPaused();
	public function isError();

	// MISC
	public function extractErrorMessage();
	public static function checkAndExecuteScheduledCampaigns();
}