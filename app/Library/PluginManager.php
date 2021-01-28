<?php

namespace Acelle\Library;

use Acelle\Model\Plugin as PluginModel;
use Composer\Autoload\ClassLoader;
use App;

class PluginManager
{
	protected $hooks;
	protected $plugins;

	public function __construct()
	{
		$this->hooks = [];
	}

	public function active($name)
	{
		$plugin = PluginModel::where('name', $name)->first();

		if (is_null($plugin)) {
			return false;
		} else {
			return $plugin->active();
		}
	}

	public function autoload()
	{
		$loader = new ClassLoader();
		$loader->addPsr4('Nghi\\Heo\\', '/tmp/src/');
		$loader->register();
	}

	public function load($name)
	{
		$pluginDir = storage_path("app/plugins/{$name}/");
		if (!file_exists($pluginDir)) {
			throw new \Exception("Plugin folder '{$pluginDir}' missing");
		}

		$composerFile = join_paths($pluginDir, 'composer.json');

		if (!file_exists($composerFile)) {
			throw new \Exception("Cannot find {$composerFile}");
		}

		$composer = json_decode(file_get_contents($composerFile), true);
		
		if (!array_key_exists('autoload', $composer)) {
			throw new \Exception("Cannot boot plugin '{$name}'. No 'autoload' found it composer.json");
		}

		if (!array_key_exists('psr-4', $composer['autoload'])) {
			throw new \Exception("Cannot boot plugin '{$name}'. No 'autoload.psr4' found it composer.json");
		}

		$loader = new ClassLoader();

		$psr4 = $composer['autoload']['psr-4'];
		foreach ($psr4 as $prefix => $path) {
			$realpath = join_paths($pluginDir, $path);
			$loader->addPsr4($prefix, $realpath);
		}

		$loader->register();

		// Then:
		// echo \Acelle\Plugin\Pluggy\Hello::VERSION;

		try {
			$serviceProviders = $composer['extra']['laravel']['providers'];
		} catch(\Exception $ex) {
			$serviceProviders = [];
		}
		
		foreach ($serviceProviders as $service) {
			App::register($service);
		}
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