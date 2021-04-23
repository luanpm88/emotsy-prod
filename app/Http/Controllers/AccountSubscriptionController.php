<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Cashier\Subscription;
use Acelle\Model\Setting;
use Acelle\Model\Plan;
use Acelle\Cashier\Cashier;
use Acelle\Cashier\Services\StripeGatewayService;
use Carbon\Carbon;
use Acelle\Cashier\SubscriptionLog;

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
        $subscription = $customer->subscription;
        // get sub gateway
        $gateway = $customer->getPaymentGateway();

        // Customer dose not have subscription
        if (!is_object($subscription) || $subscription->isEnded()) {
            $plans = Plan::getAvailablePlans();
            $planCount = Plan::getAllActive()->count();
            $colWidth = ($planCount == 0) ? 0 :  round(85 / $planCount);
            return view('account.subscription.select_plan', [
                'plans' => $plans,
                'colWidth' => $colWidth,
                'subscription' => $subscription,
            ]);
        }

        if (!$subscription->plan->isActive()) {
            return view('account.subscription.error', ['message' => __('messages.subscription.error.plan-not-active', [ 'name' => $subscription->plan->name])]);
        }
        
        // Check if subscription is new
        if ($subscription->isNew()) {
            return view('account.subscription.payment', [
                'subscription' => $subscription,
                'gateway' => $gateway,
                'plan' => $subscription->plan,
            ]);
        }
        
        // Check if subscription is new
        if ($subscription->isPending()) {
            return redirect()->away($gateway->getPendingUrl($subscription, action('AccountSubscriptionController@index')));
        }
        
        return view('account.subscription.index', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plan' => $subscription->plan,
        ]);
    }

    /**
     * Select plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function select(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $plan = Plan::findByUid($request->plan_uid);

        // create tmp subscription
        if ($customer->subscription) {
            $subscription = $customer->subscription;
        } else {
            $subscription = new Subscription();
            $subscription->user_id = $customer->getBillableId();
        }
        $subscription->plan_id = $plan->getBillableId();
        $subscription->current_period_ends_at = $subscription->getPeriodEndsAt(Carbon::now());
        $subscription->started_at = \Carbon\Carbon::now();
        $subscription->status = Subscription::STATUS_NEW;
        $subscription->save();

        // Check if subscriotion is new
        return redirect()->action('AccountSubscriptionController@payment');
    }

    /**
     * Review plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function payment(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();

        return view('account.subscription.payment', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plan' => $subscription->plan,
        ]);
    }

    /**
     * Renew plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function renew(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();

        return view('account.subscription.renew', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plan' => $subscription->plan,
        ]);
    }

    /**
     * Renew plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function doRenew(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $gateway = $customer->getPaymentGateway();
        $subscription = $customer->subscription;

        // redirect to service checkout
        return redirect()->away($gateway->getRenewUrl($subscription, action('AccountSubscriptionController@index')));
    }

    /**
     * Store customer subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function checkout(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $gateway = $customer->getPaymentGateway();
        $subscription = $customer->subscription;

        // Create subscription
        $subscription = $gateway->create($customer, $subscription->plan);

        // redirect to service checkout
        return redirect()->away($gateway->getCheckoutUrl($subscription, action('AccountSubscriptionController@index')));
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
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();
        $plans = Plan::getAvailablePlans();
        
        // Authorization
        if (!$request->user()->customer->can('changePlan', $subscription)) {
            return $this->notAuthorized();
        }
        
        return view('account.subscription.change_plan', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plans' => $plans,
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
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();

        if ($request->user()->customer->can('cancel', $subscription)) {
            $gateway->cancel($subscription);
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
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();

        if ($request->user()->customer->can('resume', $subscription)) {
            $gateway->resume($subscription);
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
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();
        
        if ($request->user()->customer->can('cancelNow', $subscription)) {
            $gateway->cancelNow($subscription);
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
     */
    public function abort(Request $request)
    {
        $customer = $request->user()->customer;
        
        // Get current subscription
        $subscription = $customer->subscription;
        
        $subscription->setEnded();

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.subscription.cancelled_now'));
        return redirect()->action('AccountSubscriptionController@index');
    }
}
