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
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        
        // Get current subscription
        $subscription = $customer->subscription();
        
        // If has no subscription
        if (!$subscription) {
            return redirect()->action('AccountSubscriptionController@new');
        }
        
        // Gateway setting                
        $subscriptionParam = $subscription->retrieve($gateway);

        return view('account.subscription.index', [
            'subscription' => $subscription,
            'plan' => $subscription->plan,
            'currentPeriodEnd' => Carbon::createFromTimestamp($subscriptionParam->currentPeriodEnd),
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
    public function subscribe(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $plan = Plan::findByUid($request->plan_uid);
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        
        // check if customer already has carf information
        if ($customer->billableUserHasCard($gateway)) {
            $customer->createSubscription($plan, $gateway);
            
            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.cancelled'));
            return redirect()->action('AccountSubscriptionController@index');
        } else {
            // new customer
            return redirect()->action('AccountSubscriptionController@subscribeWithCard', [
                'plan_uid' => $plan->uid,
            ]);
        }
    }
    
    /**
     * Subscribe with card information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function subscribeWithCard(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $plan = Plan::findByUid($request->plan_uid);
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        
        if ($request->isMethod('post')) {
            // update card
            $customer->billableUserUpdateCard($gateway, $request->all());
            
            // subscribe to plan
            $customer->createSubscription($plan, $gateway);
            
            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.cancelled'));
            return redirect()->action('AccountSubscriptionController@index');
        }
        
        return view('account.subscription.subscribe_with_card', [
            'gateway' => Setting::getPaymentGateway(Setting::get('system.payment_gateway')),
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
        $customer = $request->user()->customer;        
        // Get current subscription
        $subscription = $customer->subscription();

        if ($request->user()->customer->can('cancel', $subscription)) {        
            $subscription->cancel();
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
            $gatewayService = new $class($gateway['fields']);
        
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
            $gatewayService = new $class($gateway['fields']);
        
            $subscription->cancelNow($gatewayService);
        }

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.subscription.cancelled_now'));
        return redirect()->action('AccountSubscriptionController@index');
    }
    
    /**
     * Change plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function changePlan(Request $request, $id)
    {
        $subscription = Subscription::find($request->id);
        $plan = Plan::findByUid($request->plan_uid);
        
        if ($request->isMethod('post')) {
            if ($request->user()->customer->can('changePlan', $subscription)) {
                $gateway = Setting::getPaymentGateway($subscription->gateway);
                $class = '\\Acelle\\Cashier\\Services\\' . ucfirst($gateway['name']) . 'GatewayService';
                $gatewayService = new $class($gateway['fields']);
            
                $subscription->swap($gatewayService, $plan);
            }
    
            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.cancelled_now'));
            return redirect()->action('AccountSubscriptionController@index');
        }
        
        return view('account.subscription.change_plan', [
            'subscription' => $subscription,
        ]);
    }
}
