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
        if (!config('app.saas')) {
            return $next($request);
        }

        $subscription = $request->user()->customer->getNewOrActiveGeneralSubscription();

        // Check if customer dose not have subscription
        if (!$subscription || !$subscription->isActive() || !$subscription->planGeneral->isActive()) {
            return redirect()->action('SubscriptionController@index');
        }

        // If sending server not available

        if ($subscription->planGeneral->useSystemSendingServer()) {
            $server = $subscription->planGeneral->primarySendingServer();
            if (is_null($server)) {
                return response()->view('errors.general', [ 'message' => __('messages.plan.sending_server.no_sending_server_error', ['plan' => $subscription->planGeneral->name]) ]);
            }
        }


        return $next($request);
    }
}
