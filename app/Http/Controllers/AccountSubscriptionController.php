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
        // Check if system dosen't have payment gateway
        if (!Setting::get('system.payment_gateway')) {
            return view('noPrimaryPayment');
        }

        $customer = $request->user()->customer;
        $gateway = Cashier::getPaymentGateway();
        
        // Get current subscription
        $subscription = $customer->subscription;

        // Customer dose not have subscription
        if (!is_object($subscription)) {
            return redirect()->action('AccountSubscriptionController@selectPlan');
        }

        if (!$subscription->plan->isActive()) {
            return view('account.subscription.error', ['message' => __('messages.subscription.error.plan-not-active', [ 'name' => $subscription->plan->name])]);
        }
        
        // Check if subscriotion is new
        if ($subscription->isNew()) {
            return redirect()->action('AccountSubscriptionController@checkout');
        }
        
        // // retrieve subscription info
        // $gateway->sync($subscription);
        
        // Check if subscriotion is new
        if ($subscription->isPending()) {
            return redirect()->action('AccountSubscriptionController@pending');
        }
        
        // if subscription is ended atfer sygned
        if ($subscription->ended()) {
            return redirect()->action('AccountSubscriptionController@selectPlan');
        }
        
        return view('account.subscription.index', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plan' => $subscription->plan,
        ]);
    }
    
    /**
     * Slect plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function selectPlan(Request $request)
    {
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $plans = Plan::getAvailablePlans();
        $planCount = Plan::getAllActive()->count();
        $colWidth = ($planCount == 0) ? 0 :  round(85 / $planCount );

        if (isset($subscription) && !$subscription->ended()) {
            $request->session()->flash('alert-error', 'Already subscribed a plan!');
            return redirect()->action('AccountSubscriptionController@index');
        }

        return view('account.subscription.select_plan', [
            'plans' => $plans,
            'colWidth' => $colWidth,
        ]);
    }
    
    /**
     * Store customer subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function create(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $plan = Plan::findByUid($request->plan_uid);
        $gateway = Cashier::getPaymentGateway();

        // Create subscription
        $subscription = $gateway->create($customer, $plan);

        try {
            \Mail::to($customer->user->email)->send(new \Acelle\Mail\SubscriptionDoneMailer($subscription));
        } catch (\Exception $e) {
            $request->session()->flash('alert-error', 'Can not send email: ' . $e->getMessage());
        }
        
        // Check if subscriotion is new
        if ($subscription->isActive()) {
            return redirect()->action('AccountSubscriptionController@index');
        }

        return redirect()->action('AccountSubscriptionController@checkout');
    }
    
    /**
     * Review customer subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function review(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $plan = Plan::findByUid($request->plan_uid);
        
        if (isset($subscription) && !$subscription->ended()) {
            $request->session()->flash('alert-error', 'Already subscribed a plan!');
            return redirect()->action('AccountSubscriptionController@index');
        }
        
        return view('account.subscription.review', [
            'plan' => $plan,
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
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        
        // Authorization
        if (!$request->user()->customer->can('checkout', $subscription)) {
            return $this->notAuthorized();
        }
        
        return redirect()->action("\Acelle\Cashier\Controllers\\" . ucfirst(config('cashier.gateway')) . "Controller@checkout", [
            'subscription_id' => $subscription->uid,
            'return_url' => action('AccountSubscriptionController@index'),
        ]);
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
        $gateway = Cashier::getPaymentGateway();
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
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;

        if ($request->user()->customer->can('resume', $subscription)) {
            $subscription->resume();
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

        if ($request->user()->customer->can('cancelNow', $subscription)) {
            $subscription->cancelNow();
        }

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.subscription.cancelled_now'));
        return redirect()->action('AccountSubscriptionController@index');
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
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $gateway = Cashier::getPaymentGateway();
        
        // Authorization
        if (!$request->user()->customer->can('renew', $subscription)) {
            return $this->notAuthorized();
        }
        
        // check if status is not pending
        if ($gateway->hasPending($subscription)) {
            throw new \Exception("Can not renew pending subscription!");
        }
        
        $gateway->renew($subscription);
        
        return redirect()->away($gateway->getPendingUrl($subscription, action('AccountSubscriptionController@index')));
    }

    /**
     * Download raw invoices.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function downloadRawInvoice(Request $request)
    {
        $subscription = $request->user()->customer->subscription;
        $gatewayService = Cashier::getPaymentGateway();


        return response(var_export($gatewayService->getRawInvoices($subscription)), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="payment_logs.txt"',
        ]);
    }
}
