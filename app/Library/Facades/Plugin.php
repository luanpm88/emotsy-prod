<?php

namespace Acelle\Library\Facades;

use Illuminate\Support\Facades\Facade;
use Acelle\Library\PluginManager;

class Plugin extends Facade
{
    protected static function getFacadeAccessor() { return PluginManager::class; }
}
