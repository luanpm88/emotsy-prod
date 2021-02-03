<?php

namespace Acelle\Library;

class HookManager
{
    protected $hooks;
    protected $plugins;

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
