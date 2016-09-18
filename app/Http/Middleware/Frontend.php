<?php

namespace Acelle\Http\Middleware;

use Closure;

class Frontend
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
        $user = $request->user();
        
        // check if user not authorized for customer access
        if (isset($user) && !$user->can("customer_access", $user)) {
            return redirect()->action('Controller@notAuthorized');
        }
        
        // Site offline
        if (\Acelle\Model\Setting::get('site_online') == 'false' &&
            (isset($user) && $user->customer->getOption('access_when_offline') != 'yes')
        ) {
            return redirect()->action('Controller@offline');
        }        

        return $next($request);
    }
}
