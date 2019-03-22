<?php

namespace Acelle\Http\Middleware;

use Closure;

class Subscription
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
        $subscription = $request->user()->customer->subscription();        
        
        if (is_object($subscription)) {
            if(!$subscription->active()) {
                return redirect()->action('AccountSubscriptionController@noPlan');
            }
        } else {
            return redirect()->action('AccountSubscriptionController@noPlan');
        }

        return $next($request);
    }
}
