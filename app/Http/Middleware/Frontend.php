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
        // Site offline
        if (\Acelle\Model\Setting::get('site_online') == 'false' &&
            (null !== $request->user() && $request->user()->getOption('backend', 'setting_access_when_offline') != 'yes')
        ) {
            return redirect()->action('Controller@offline');
        }
        
        $action_name = $request->route()->getAction()["controller"];
        // check if user not authorized for backend access
        if (null !== $request->user() &&
            !$request->user()->userGroup->frontend_access &&
            strpos($action_name, 'AccountController') == false &&
            $action_name != 'Acelle\Http\Controllers\MailListController@quickView' &&
            $action_name != 'Acelle\Http\Controllers\MailListController@listGrowthChart' &&
            $action_name != 'Acelle\Http\Controllers\MailListController@statisticsChart' &&
            $action_name != 'Acelle\Http\Controllers\UserController@avatar' &&
            $action_name != 'Acelle\Http\Controllers\CampaignController@quickView' &&
            $action_name != 'Acelle\Http\Controllers\CampaignController@chart24h' &&
            $action_name != 'Acelle\Http\Controllers\CampaignController@chart' &&
            $action_name != 'Acelle\Http\Controllers\CampaignController@chartCountry' &&
            $action_name != 'Acelle\Http\Controllers\CampaignController@chartClickCountry'
        ) {
            if($request->user()->userGroup->backend_access) {
                return redirect()->action('Admin\HomeController@index');
            } else {
                return redirect()->action('Controller@notAuthorized');
            }            
        }
        
        return $next($request);
    }
}
