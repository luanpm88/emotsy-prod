<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Cashier\Subscription;
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
        
        // check subscription status
        if (is_object($subscription)) {        
            if(!$subscription->active()) {
                try {
                    $subscription->sync($gateway);
                } catch (\Exception $ex) {
                    $subscription->markAsCancelled();
                    
                    // can found remote subscription
                    return redirect()->action('AccountSubscriptionController@index');
                }
                
                if ($subscription->isNew()) {
                    return redirect()->action('AccountSubscriptionController@checkout');
                } elseif ($subscription->isPending()) {
                    return redirect()->action('AccountSubscriptionController@pending');
                } else {
                    return redirect()->action('AccountSubscriptionController@new');
                }
            }
        } else {
            return redirect()->action('AccountSubscriptionController@new');
        }
        
        // retrieve subscription info
        $subscriptionParam = $customer->retrieveSubscription($gateway);
        
        return view('account.subscription.index', [
            'subscription' => $subscription,
            'gateway' => $gateway,
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
    public function pending(Request $request)
    {
        $customer = $request->user()->customer;
        $subscription = $customer->subscription();
        $gatewayService = \App::make('Acelle\Cashier\PaymentGateway');
        
        if (!isset($subscription)) {
            return redirect()->action('AccountSubscriptionController@index');
        }
        
        //
        if (!$subscription->isPending()) {
            return redirect()->action('AccountSubscriptionController@index');
        }
        
        return view('account.subscription.pending', [
            'subscription' => $subscription,
            'gateway' => Setting::getPaymentGateway(Setting::get('system.payment_gateway')),
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
        $customer = $request->user()->customer;
        $subscription = $customer->subscription();
        
        // Authorization
        if (isset($subscription)) {
            return redirect()->action('AccountSubscriptionController@index');
        }
        
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
        $subscription = $customer->subscription();
        
        // Create subscription
        if (!is_object($subscription)) {
            $subscription = $customer->createSubscription($plan, $gateway);
        }
        
        return redirect()->action('AccountSubscriptionController@checkout');
    }
    
    /**
     * Subscribe with card information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function updateCard(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        $subscription = $customer->subscription();
        
        if ($request->isMethod('post')) {
            // update card
            $customer->billableUserUpdateCard($gateway, $request->all());
            
            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.created'));
            return redirect()->action('AccountSubscriptionController@checkout');
        }
        
        return view('account.subscription.update_card', [
            'gateway' => Setting::getPaymentGateway(Setting::get('system.payment_gateway')),
            'plan' => $subscription->plan,
            'subscription' => $subscription,
        ]);
    }
    
    /**
     * Subscription checkout page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function checkout(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        $subscription = $customer->subscription();
        
        // check if customer already has carf information
        if (!$customer->billableUserHasCard($gateway)) {
            // update card info
            return redirect()->action('AccountSubscriptionController@updateCard');
        }
        
        if ($request->isMethod('post')) {
            // subscribe to plan
            $subscription->charge($gateway);
            
            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.created'));
            return redirect()->action('AccountSubscriptionController@pending');
        }
        
        return view('account.subscription.checkout', [
            'subscription' => $subscription,
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
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        $subscription = $customer->subscription();

        if ($request->user()->customer->can('cancel', $subscription)) {        
            $subscription->cancel($gateway);
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
        $customer = $request->user()->customer;
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        $subscription = $customer->subscription();

        if ($request->user()->customer->can('resume', $subscription)) {
            $subscription->resume($gateway);
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
        $customer = $request->user()->customer;        
        // Get current subscription
        $subscription = $customer->subscription();
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');

        if ($request->user()->customer->can('cancelNow', $subscription)) {
            $subscription->cancelNow($gateway);
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
    public function changePlan(Request $request)
    {
        $customer = $request->user()->customer;        
        // Get current subscription
        $subscription = $customer->subscription();
        $plan = Plan::findByUid($request->plan_uid);
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        
        // Authorization
        if (!$request->user()->customer->can('changePlan', $subscription)) {
            return redirect()->action('AccountSubscriptionController@index');
        }
        
        if ($request->isMethod('post')) {            
            $customer->changePlan($plan, $gateway);
            
            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.plan_changed'));
            return redirect()->action('AccountSubscriptionController@index');
        }
        
        return view('account.subscription.change_plan', [
            'subscription' => $subscription,
        ]);
    }
}
