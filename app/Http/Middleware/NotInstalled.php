<?php

namespace Acelle\Http\Middleware;

use Closure;

class NotInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!isInitiated()) {
            return redirect()->action('InstallController@starting');
        }

        $response = $next($request)
            ->header('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
