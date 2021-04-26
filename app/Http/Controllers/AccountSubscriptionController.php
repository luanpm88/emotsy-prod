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
        // init
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();

        // 1. SELECT PLAN: No active subscription
        if (!$subscription || $subscription->isEnded()) {
            $plans = Plan::getAvailablePlans();
            $planCount = count($plans);

            return view('account.subscription.select_plan', [
                'plans' => Plan::getAvailablePlans(),
                'colWidth' => ($planCount == 0) ? 0 :  round(85 / $planCount),
                'subscription' => $subscription,
            ]);
        }

        // check subscription status. @todo: move to cron
        $subscription->check();

        // 2. IF PLAN NOT ACTIVE
        if (!$subscription->plan->isActive()) {
            return view('account.subscription.error', ['message' => __('messages.subscription.error.plan-not-active', [ 'name' => $subscription->plan->name])]);
        }
        
        // 3. NEW/PENDING SUBSCRIPTION
        if ($subscription->isNew() || $subscription->isPending()) {
            $invoice = $subscription->initInvoice();

            // PAYMENT: init invoice is new
            if ($invoice->isNew()) {
                return view('account.subscription.payment', [
                    'subscription' => $subscription,
                    'gateway' => $gateway,
                    'invoice' => $invoice,
                ]);
            }

            // CHECKOUT: init invoice is pending || claimed
            if ($invoice->isPending() || $invoice->isClaimed()) {
                // 3.3 CHECK GATEWAY. need gateway for charging
                if ($gateway == null) {
                    return view('account.subscription.payment', [
                        'subscription' => $subscription,
                        'gateway' => $gateway,
                        'plan' => $subscription->plan,
                    ]);
                }
                
                // redirect to service checkout
                return redirect()->away($gateway->getCheckoutUrl(
                    $invoice,
                    action('AccountSubscriptionController@index')
                ));     
            }
        }
        
        // // Check if subscription is new
        // if ($subscription->isPending()) {
        //     return redirect()->away($gateway->getPendingUrl($subscription, action('AccountSubscriptionController@index')));
        // }
        
        return view('account.subscription.index', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plan' => $subscription->plan,
            'renewInvoice' => $subscription->renewInvoice(),
        ]);
    }

    /**
     * Select plan.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function init(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $plan = Plan::findByUid($request->plan_uid);

        // create new subscription
        $subscription = Subscription::initNew($customer, $plan);

        // Check if subscriotion is new
        return redirect()->action('AccountSubscriptionController@index');
    }

    /**
     * Checkout subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function checkout(Request $request)
    {
        // Get current customer
        $subscription = $request->user()->customer->subscription;
        $initInvoice = $subscription->initInvoice();

        // set invoice as pending
        $initInvoice->setPending();

        // return to subscription
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
        // Get current customer
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();
        $renewInvoice = $subscription->renewInvoice();

        if ($request->isMethod('post')) {
            // set invoice as pending
            $renewInvoice->setPending();

            // redirect to service checkout
            return redirect()->away($gateway->getCheckoutUrl(
                $renewInvoice,
                action('AccountSubscriptionController@index')
            )); 
        }

        return view('account.subscription.renew', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plan' => $subscription->plan,
            'renewInvoice' => $renewInvoice,
        ]);
    }

    /**
     * Change plan payment.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function changePlanPayment(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $gateway = $customer->getPaymentGateway();
        $changePlanInvoice = $subscription->changePlanInvoice();
        $changePlanInvoiceItem = $subscription->changePlanInvoiceItem();
        $newPlan = Plan::findByUid($changePlanInvoiceItem->getMetadata()['plan_uid']);

        if ($request->isMethod('post')) {
            // set invoice as pending
            $changePlanInvoice->setPending();

            // redirect to service checkout
            return redirect()->away($gateway->getCheckoutUrl(
                $changePlanInvoice,
                action('AccountSubscriptionController@index')
            )); 
        }

        return view('account.subscription.changePlanPayment', [
            'subscription' => $subscription,
            'gateway' => $gateway,
            'plan' => $newPlan,
            'changePlanInvoice' => $changePlanInvoice,
        ]);
    }

    // /**
    //  * Renew plan.
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  *
    //  * @return \Illuminate\Http\Response
    //  **/
    // public function doRenew(Request $request)
    // {
    //     // Get current customer
    //     $customer = $request->user()->customer;
    //     $gateway = $customer->getPaymentGateway();
    //     $subscription = $customer->subscription;

    //     // redirect to service checkout
    //     return redirect()->away($gateway->getRenewUrl($subscription, action('AccountSubscriptionController@index')));
    // }
    
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

        //
        if ($request->isMethod('post')) {
            $newPlan = Plan::findByUid($request->plan_uid);

            try {
                // set invoice as pending
                $subscription->createChangePlanInvoice($newPlan, 
                    array_merge(
                        \Acelle\Cashier\Cashier::calcChangePlan($subscription, $newPlan),
                        [
                            'plan_uid' => $newPlan->uid,
                        ]
                    )
                );
            } catch (\Exception $e) {
                $request->session()->flash('alert-error', $e->getMessage());
                return redirect()->action('AccountSubscriptionController@index');
            }

            // return to subscription
            return redirect()->action('AccountSubscriptionController@changePlanPayment');
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

        if (!$request->user()->customer->can('cancel', $subscription)) {
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
    public function cancelInvoice(Request $request, $uid)
    {
        $invoice = \Acelle\Model\Invoice::findByUid($uid);
        $subscription = $request->user()->customer->subscription;

        if (!$request->user()->customer->can('cancel', $invoice)) {
            return $this->notAuthorized();
        }

        $invoice->setCanceled();

        // log
        $subscription->addLog(SubscriptionLog::TYPE_PLAN_CHANGE_CANCELED, [
            'plan' => $subscription->plan->getBillableName(),
            'price' => $subscription->plan->getBillableFormattedPrice(),
        ]);

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.invoice.cancelled'));
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
        $subscription = $customer->subscription;
        
        if ($request->user()->customer->can('cancelNow', $subscription)) {
            $subscription->cancelNow();
        }

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.subscription.cancelled_now'));
        return redirect()->action('AccountSubscriptionController@index');
    }
}
