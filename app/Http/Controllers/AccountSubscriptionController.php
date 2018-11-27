<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Model\Subscription;
use Acelle\Model\Setting;
use Acelle\Model\Plan;
use Acelle\Cashier\Services\StripeGatewayService;

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
        $subscription = $customer->getCurrentSubscriptionIgnoreStatus();

        // If has no subscription
        if (!$subscription) {
            return redirect()->action('AccountSubscriptionController@new');
        }

        return view('account.subscription.index', [
            'subscription' => $subscription,
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
        // init
        $customer = $request->user()->customer;
        $subscription = new Subscription();
        $subscription->customer_id = $customer->id;

        // authorize
        if (!$customer->can('create', $subscription)) {
            return $this->notAuthorized();
        }

        return view('account.subscription.new', [
            'subscription' => $subscription
        ]);
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
        $stripeGateway = new StripeGatewayService($gateway['fields']['secret_key']);
        $customer->newSubscription($plan, $stripeGateway)->create($request->stripeToken);
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
        $subscription = new Subscription();
        $subscription->customer_id = $request->user()->customer->id;
        $subscription->fillAttributes($request->all());
        
        $gateways = Setting::getActivePaymentGateways();

        return view('account.subscription.preview', [
            'subscription' => $subscription,
            'gateways' => $gateways,
        ]);
    }
}
