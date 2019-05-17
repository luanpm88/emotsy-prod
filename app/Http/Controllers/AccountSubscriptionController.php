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
     * No plan accounce page.
     *
     * @return \Illuminate\Http\Response
     */
    public function noPlan()
    {
        return view('account.subscription.no_plan');
    }

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
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');

        // Get current subscription
        $subscription = $customer->subscription();

        // Customer dose not have subscription
        if (!is_object($subscription)) {
            return redirect()->action('AccountSubscriptionController@new');
        }

        // is New
        if ($subscription->isNew()) {
            return redirect()->action('AccountSubscriptionController@card');
        }

        // is New
        if ($subscription->isPending()) {
            return redirect()->action('AccountSubscriptionController@pending');
        }

        // try to sync subscription information
        try {
            $subscription->sync($gateway);
        } catch (\Exception $ex) {
            $subscription->markAsCancelled();
            $request->session()->flash('alert-error', $ex->getMessage());
        }

        // retrieve subscription info
        $subscriptionParam = $customer->retrieveSubscription($gateway);

        return view('account.subscription.index', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plan' => $subscription->plan,
            'currentPeriodEnd' => Carbon::createFromTimestamp($subscriptionParam->currentPeriodEnd),
            'hasPendingPaymentForFuture' => $subscription->checkPendingPaymentForFuture($gateway),
            'goingToExpire' => $subscription->goingToExpire(Setting::get('system.end_period_last_days')),
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

        return view('account.subscription.new');
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
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        $subscription = $customer->subscription();

        // Create subscription
        $subscription = $customer->createSubscription($plan, $gateway);

        try {
            \Mail::to($customer->user->email)->send(new \Acelle\Mail\SubscriptionDoneMailer($subscription));
        } catch (\Exception $e) {
            $request->session()->flash('alert-error', 'Can not send email: ' . $e->getMessage());
        }

        return redirect()->action('AccountSubscriptionController@card');
    }

    /**
     * Card information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function card(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $subscription = $customer->subscription();
        $gatewayService = \App::make('Acelle\Cashier\PaymentGateway');

        return view('account.subscription.card', [
            'gateway' => Setting::getPaymentGateway(Setting::get('system.payment_gateway')),
            'customer' => $customer,
            'gatewayService' => $gatewayService,
            'subscription' => $subscription,
        ]);
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
            // $request->session()->flash('alert-success', trans('messages.subscription.created'));
            return redirect()->action('AccountSubscriptionController@pay');
        }

        return view('account.subscription.update_card', [
            'gateway' => Setting::getPaymentGateway(Setting::get('system.payment_gateway')),
            'plan' => $subscription->plan,
            'subscription' => $subscription,
        ]);
    }

    /**
     * Subscription pay.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function pay(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        $subscription = $customer->subscription();

        if ($request->isMethod('post')) {
            // subscribe to plan
            $subscription->charge($gateway);

            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.finished', ['plan' => $subscription->plan->name]));
            return redirect()->action('AccountSubscriptionController@index');
        }

        return view('account.subscription.pay', [
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

        // retrieve subscription info
        $subscription->retrieve($gatewayService);

        return view('account.subscription.pending', [
            'subscription' => $subscription,
            'gateway' => Setting::getPaymentGateway(Setting::get('system.payment_gateway')),
            'gatewayService' => $gatewayService,
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
            if (!$gateway->isSupportRecurring()) {
                // update card info
                return redirect()->action('AccountSubscriptionController@changePlanNoRecurring', [$plan->uid]);
            }

            $customer->changePlan($plan, $gateway);

            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.plan_changed'));
            return redirect()->action('AccountSubscriptionController@index');
        }

        return view('account.subscription.change_plan', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * Change plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function changePlanNoRecurring(Request $request)
    {
        $customer = $request->user()->customer;
        // Get current subscription
        $subscription = $customer->subscription();
        $newPlan = Plan::findByUid($request->plan_id);
        $gatewayService = \App::make('Acelle\Cashier\PaymentGateway');

        // @todo can not change to lower one
        if ($customer->calcChangePlan($newPlan)['amount'] < 0) {
            // Redirect to my subscription page
            $request->session()->flash('alert-error', trans('messages.subscription.plan_changed.lower_error'));
            return redirect()->action('AccountSubscriptionController@index');
        }

        // Authorization
        if (!$request->user()->customer->can('changePlan', $subscription)) {
            return redirect()->action('AccountSubscriptionController@index');
        }

        if ($request->isMethod('post')) {
            $customer->changePlan($newPlan, $gatewayService);

            // Redirect to my subscription page
            $request->session()->flash('alert-success', trans('messages.subscription.plan_changed'));
            return redirect()->action('AccountSubscriptionController@index');
        }

        return view('account.subscription.change_plan_no_recurring', [
            'subscription' => $subscription,
            'gateway' => Setting::getPaymentGateway(Setting::get('system.payment_gateway')),
            'gatewayService' => $gatewayService,
            'customer' => $customer,
            'newPlan' => $newPlan,
        ]);
    }

    /**
     * Top-up subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function renew(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $gatewayService = \App::make('Acelle\Cashier\PaymentGateway');
        $subscription = $customer->subscription();

        if ($request->isMethod('post')) {
            // subscribe to plan
            $subscription->renew($gatewayService);

            // Redirect to my subscription page
            return redirect()->action('AccountSubscriptionController@index');
        }

        return view('account.subscription.renew', [
            'subscription' => $subscription,
            'gateway' => Setting::getPaymentGateway(Setting::get('system.payment_gateway')),
            'gatewayService' => $gatewayService,
        ]);
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
        $subscription = $request->user()->customer->subscription();
        $gatewayService = \App::make('Acelle\Cashier\PaymentGateway');


        return response(var_export($subscription->getRawInvoices($gatewayService)), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="payment_logs.txt"',
        ]);
    }

    /**
     * Claim subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function paymentClaim(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $subscription = $customer->subscription();

        // subscribe to plan
        $subscription->claimPayment();

        // Redirect to my subscription page
        return redirect()->action('AccountSubscriptionController@index');
    }
}
