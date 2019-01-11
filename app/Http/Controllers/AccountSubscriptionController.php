<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Model\Subscription;
use Acelle\Model\Setting;
use Acelle\Model\Plan;
use Acelle\Cashier\Services\StripeGatewayService;
use Carbon\Carbon;
use Acelle\Cashier\SubscriptionParam;

class AccountSubscriptionController extends Controller
{
    /**
     * Customer subscription main page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function index(Request $request)
    {
        $customer = $request->user()->customer;
        
        // Get current subscription
        $subscription = $customer->subscription();

        // If has no subscription
        if (!$subscription) {
            return redirect()->action('AccountSubscriptionController@new');
        }
        
        // Gateway setting
        $gateway = Setting::getPaymentGateway($subscription->gateway);
        $class = '\\Acelle\\Cashier\\Services\\' . ucfirst($gateway['name']) . 'GatewayService';
        $gatewayService = new $class($gateway['fields']['secret_key']);
        
        $subscriptionParam = $gatewayService->retrieveSubscription($subscription->remote_id);
        

        return view('account.subscription.index', [
            'subscription' => $subscription,
            'plan' => $subscription->plan,
            'currentPeriodEndAt' => Carbon::createFromTimestamp($subscriptionParam->currentPeriodEnd),
        ]);
    }

    /**
     * New customer subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function new(Request $request)
    {
        return view('account.subscription.new');
    }
    
    /**
     * Store customer subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function process(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $plan = Plan::findByUid($request->plan_uid);
        
        // Check if plan is free
        if ($plan->isFree()) {
            $subscription = $customer->createSubscriptionWithoutPayment($plan);
            
            // enable right once
            $subscription->enable();
            
            $request->session()->flash('alert-success', trans('messages.subscription.created'));
            return redirect()->action('AccountSubscriptionController@index');
        } else {
            return redirect()->action('AccountSubscriptionController@checkout', [
                'plan_uid' => $plan->uid,
                'gateway' => $request->gateway
            ]);
        }
        
        
    }
    
    /**
     * Create/Pay customer subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function subscribe(Request $request)
    {
        // init
        $customer = $request->user()->customer;
        $gateway = Setting::getPaymentGateway($request->gateway);
        $plan = Plan::findByUid($request->plan_uid);
        
        // subscribe to plan
        $class = '\\Acelle\\Cashier\\Services\\' . ucfirst($gateway['name']) . 'GatewayService';
        $gatewayService = new $class($gateway['fields']['secret_key']);
        $subscription = $customer->newSubscription($plan, $gatewayService)->create($request->stripeToken);
        
        $request->session()->flash('alert-success', trans('messages.subscription.created'));
        return redirect()->action('AccountSubscriptionController@index');
    }
    
    /**
     * Subscription checkout with payment.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function checkout(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $plan = Plan::findByUid($request->plan_uid);
        $gateway = Setting::getPaymentGateway($request->gateway);
        
        return view('account.subscription.checkout', [
            'gateway' => $gateway,
            'plan' => $plan,
        ]);
    }
    
    /**
     * Subscription preview.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request)
    {
        // Get subscription
        $plan = Plan::findByUid($request->plan_uid);
        $gateways = Setting::getActivePaymentGateways();

        return view('account.subscription.preview', [
            'gateways' => $gateways,
            'plan' => $plan,
        ]);
    }
    
    /**
     * Cancel subscription at the end of current period.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request)
    {
        $subscription = \Acelle\Model\Subscription::find($request->id);

        if ($request->user()->customer->can('cancel', $subscription)) {
            $gateway = Setting::getPaymentGateway($subscription->gateway);
            $class = '\\Acelle\\Cashier\\Services\\' . ucfirst($gateway['name']) . 'GatewayService';
            $gatewayService = new $class($gateway['fields']['secret_key']);
        
            $subscription->cancel($gatewayService);
        }

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.subscription.cancelled'));
        return redirect()->action('AccountSubscriptionController@index');
    }
    
    /**
     * Cancel subscription at the end of current period.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resume(Request $request)
    {
        $subscription = \Acelle\Model\Subscription::find($request->id);

        if ($request->user()->customer->can('resume', $subscription)) {
            $gateway = Setting::getPaymentGateway($subscription->gateway);
            $class = '\\Acelle\\Cashier\\Services\\' . ucfirst($gateway['name']) . 'GatewayService';
            $gatewayService = new $class($gateway['fields']['secret_key']);
        
            $subscription->resume($gatewayService);
        }

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.subscription.resumed'));
        return redirect()->action('AccountSubscriptionController@index');
    }
    
    /**
     * Cancel now subscription at the end of current period.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelNow(Request $request)
    {
        $subscription = \Acelle\Model\Subscription::find($request->id);

        if ($request->user()->customer->can('cancelNow', $subscription)) {
            $gateway = Setting::getPaymentGateway($subscription->gateway);
            $class = '\\Acelle\\Cashier\\Services\\' . ucfirst($gateway['name']) . 'GatewayService';
            $gatewayService = new $class($gateway['fields']['secret_key']);
        
            $subscription->cancelNow($gatewayService);
        }

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.subscription.cancelled_now'));
        return redirect()->action('AccountSubscriptionController@index');
    }
}
