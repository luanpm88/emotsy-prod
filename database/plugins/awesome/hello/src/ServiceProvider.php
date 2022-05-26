<?php

namespace Awesome\Hello;

use Illuminate\Support\ServiceProvider as Base;
use Acelle\Library\Facades\Hook;

class ServiceProvider extends Base
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hello');

        // routes
        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        // translation
        $this->loadTranslationsFrom(storage_path('app/awesome/hello/lang/'), 'hello');

        // Make sure the translation file is copied from the master location
        Hook::register('add_translation_file', function() {
            return [
                "id" => 'awesome/hello translation file',
                "plugin_name" => "Awesome/Hello",
                "file_title" => "Translation for awesome/hello plugin",
                "translation_folder" => storage_path('app/awesome/hello/lang/'),
                "file_name" => "messages.php",
                "master_translation_file" => realpath(__DIR__.'/../resources/lang/en/messages.php'),
            ];
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
