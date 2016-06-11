<?php

namespace Mailixa\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        app('view')->composer('*', function($view)
        {
            $action = app('request')->route()->getAction();
    
            $controller = class_basename($action['controller']);
    
            list($controller, $action) = explode('@', $controller);
    
            $view->with(compact('controller', 'action'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
