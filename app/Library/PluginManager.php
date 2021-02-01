<?php

namespace Acelle\Library;

use Acelle\Model\Plugin as PluginModel;
use Composer\Autoload\ClassLoader;
use App;

class PluginManager
{
    protected $hooks;
    protected $plugins;

    const STORAGE_PATH = 'app/plugins'; // do not use this constant, use getStoragePath instead

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
            return $plugin->isActive();
        }
    }

    public function getStoragePath($name, $subpath = null)
    {
        $base = storage_path(join_paths(self::STORAGE_PATH, $name));
        $path = join_paths($base, $subpath);
        return $path;
    }

    public function getComposerJsonFilePath($name)
    {
        return $this->getStoragePath($name, 'composer.json');
    }

    public function getComposerJson($name)
    {
        $pluginDir = $this->getStoragePath($name);
        if (!file_exists($pluginDir)) {
            throw new \Exception("Plugin folder '{$pluginDir}' missing");
        }

        $composerFile = $this->getComposerJsonFilePath($name);

        if (!file_exists($composerFile)) {
            throw new \Exception("Cannot find {$composerFile} in plugin folder. Invalid plugin?");
        }

        try {
            $composerJson = json_decode(file_get_contents($composerFile), true);
        } catch (\Exception $ex) {
            throw new \Exception("Cannot parse {$composerFile}"); // for easy debugging
        }

        return $composerJson;
    }

    public function autoload($includeInactivePlugin = false)
    {
        $activePlugins = PluginModel::active()->get();
        foreach ($activePlugins as $plugin) {
            $this->load($plugin->name);
        }
    }

    public function load($name, $boot = true)
    {
        $composerJson = $this->getComposerJson($name);

        if (!array_key_exists('autoload', $composerJson)) {
            throw new \Exception("Cannot boot plugin '{$name}'. No 'autoload' found it composer.json");
        }

        if (!array_key_exists('psr-4', $composerJson['autoload'])) {
            throw new \Exception("Cannot boot plugin '{$name}'. No 'autoload.psr4' found it composer.json");
        }

        $loader = new ClassLoader();

        $psr4 = $composerJson['autoload']['psr-4'];
        foreach ($psr4 as $prefix => $path) {
            $realpath = $this->getStoragePath($name, $path);
            $loader->addPsr4($prefix, $realpath);
        }

        $loader->register();

        if (!$boot) {
            return;
        }

        // Load and boot ServiceProvider
        $serviceProviders = [];
        if (
            array_key_exists('extra', $composerJson) &&
            array_key_exists('laravel', $composerJson['extra']) &&
            array_key_exists('providers', $composerJson['extra']['laravel'])) {
            $serviceProviders = $composerJson['extra']['laravel']['providers'];
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

    public function executeHook($name, $params = [])
    {
        $results = [];
        if (array_key_exists($name, $this->hooks)) {
            foreach ($this->hooks[$name] as $callback) {
                $results[] = call_user_func_array($callback, $params);
            }
        }

        return $results;
    }

    public function getHook($name)
    {
        return $this->hooks[$name];
    }

    public function installFromDir($name)
    {
    	$composer = $this->getComposerJson($name);
    	$record = PluginModel::createFromComposerJson($composer);
    }
}
