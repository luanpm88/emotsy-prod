<?php

namespace Mailixa\Http\Middleware;

use Closure;

class NotInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->alreadyInstalled($request)) {
            exec('php '.base_path().'/artisan config:cache');
            // exec('php '.base_path().'/artisan key:generate');
            return redirect()->action('InstallController@starting');
        }
        
        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled($request)
    {
        $install_finish = $request->session()->get('install_finish');
        
        if (isset($install_finish)) {
            // write file
            $file_path = storage_path("installed");
            $file = fopen($file_path, "w") or die("Unable to open file!");
            fwrite($file, "");
            fclose($file);
            
            $request->session()->forget('install_finish');
        }
        return file_exists(storage_path('installed'));
    }
}
