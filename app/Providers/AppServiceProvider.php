<?php

namespace Acelle\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if ( config('app.env') === 'local' ) {
            \DB::connection()->enableQueryLog();
        }

        if ( config('app.env') === 'local' ) {
            \DB::connection()->enableQueryLog();
        }

        ini_set('memory_limit', '-1');

        app('view')->composer('*', function ($view) {
            $action = app('request')->route()->getAction();

            $controller = class_basename($action['controller']);

            list($controller, $action) = explode('@', $controller);

            $view->with(compact('controller', 'action'));
        });

        // extend substring validator
        Validator::extend('substring', function ($attribute, $value, $parameters, $validator) {
            $tag = $parameters[0];
            if (strpos($value, $tag) === false) {
                return false;
            }

            return true;
        });
        Validator::replacer('substring', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':tag', $parameters[0], $message);
        });

        if ( config('app.env') === 'local' ) {
            \Event::listen('kernel.handled', function ( $request, $response ) {
                if ( $request->has('sql-debug') ) {
                    $queries = \DB::getQueryLog();
                    dd($queries);
                }
            });
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
