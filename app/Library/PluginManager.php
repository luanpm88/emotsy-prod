<?php

namespace Acelle\Library;

class PluginManager
{
	protected $hooks;

	public function __construct()
	{
		$this->hooks = [];
	}

	public function registerHook($name, $callback)
	{
		// Example:
		// $manager->registerHook('hello', function($param1, $param2) { ... });
		// $manager->executeHook('hello', [ $param1, $param2 ]);

		if (array_key_exists($name, $this->hooks)) {
			$this->hooks[$name][] = $callback;
		} else {
			$this->hooks[$name] = [$callback];	
		}
	}

	public function executeHook($name, $params)
	{
		if (array_key_exists($name, $this->hooks)) {
			foreach($this->hooks[$name] as $callback) {
				call_user_func_array($callback, $params);
			}
		}
	}

	public function getHook($name)
	{
		return $this->hooks[$name];
	}
}