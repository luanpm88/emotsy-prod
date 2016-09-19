<?php

namespace Acelle\Http\Middleware;

use Closure;

class Backend
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
        
        // check if user not authorized for admin access
        if (isset($user) && !$user->can("admin_access", $user)) {
            return redirect()->action('Controller@notAuthorized');
        }
        
        // Site offline
        if (\Acelle\Model\Setting::get('site_online') == 'false' &&
            (null !== $request->user() && $request->user()->admin->getPermission('setting_access_when_offline') != 'yes')
        ) {
            return redirect()->action('Controller@offline');
        }
        
        // Language
        try {
            if (is_object($user->admin->language)) {
                \App::setLocale($user->admin->language->code);
                //\Carbon\Carbon::setLocale($user->admin->language->code);
            }
        } catch (\Exception $e) {
        }

        return $next($request);
    }
}
