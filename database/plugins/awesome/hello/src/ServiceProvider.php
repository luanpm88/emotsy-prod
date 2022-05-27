<?php

namespace {{ author_class }}\{{ name_class }};

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
        $this->loadViewsFrom(__DIR__.'/../resources/views', '{{ name }}');

        // routes
        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        // translation
        $this->loadTranslationsFrom(storage_path('app/data/plugins/{{ author }}/{{ name }}/lang/'), '{{ name }}');

        // Make sure the translation file is copied from the master location
        Hook::register('add_translation_file', function() {
            return [
                "id" => '#{{ plugin }}_translation_file',
                "plugin_name" => "{{ plugin }}",
                "file_title" => "Translation for {{ plugin }} plugin",
                "translation_folder" => storage_path('app/data/plugins/{{ author }}/{{ name }}/lang/'),
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
