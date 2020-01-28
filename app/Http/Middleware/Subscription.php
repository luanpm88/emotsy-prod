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
        $subscription = $request->user()->customer->subscription;
        
        // Check if customer dose not have subscription
        if (!is_object($subscription) || !$subscription->isActive() || !$subscription->plan->isActive()) {
            return redirect()->action('AccountSubscriptionController@index');
        }

        // If sending server not available
        $server = $subscription->plan->primarySendingServer();
        if (is_null($server)) {
           return response()->view('errors.general', [ 'message' => "The plan you currently subscribe to ({$subscription->plan->name}) does not have any sending server. Log in as admin, then go to Plan setting page, make sure you add a sending server to this plan" ]);
        }

        return $next($request);
    }
}
