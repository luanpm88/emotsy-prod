<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Model\Subscription;
use Acelle\Model\Setting;
use Acelle\Model\Plan;
use Acelle\Cashier\Cashier;
use Acelle\Cashier\Services\StripeGatewayService;
use Carbon\Carbon;
use Acelle\Model\SubscriptionLog;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        // init
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;

        // 1. HAVE NOT HAD SUBSCRIPTION YET OR SUBSCRIPTION IS ENDED
        if (!$subscription ||
            $subscription->isEnded()
        ) {
            return view('subscription.selectPlan', [
                'plans' => Plan::getAvailablePlans(),
                'subscription' => $subscription,
            ]);
        }

        // @todo không để đây, chỉ test thôi, cần move qua cronjob
        // 1. 1 End luôn subscription nếu đã hết hạn
        //    2 Sinh ra RENEW invoice
        //    3 Xử lý thanh toán
        $subscription->check();

        // 2. IF PLAN NOT ACTIVE
        if (!$subscription->plan->isActive()) {
            return response()->view('errors.general', [ 'message' => __('messages.subscription.error.plan-not-active', [ 'name' => $subscription->plan->name]) ]);
        }
        
        // 3. SUBSCRIPTION IS NEW
        if ($subscription->isNew()) {
            $invoice = $subscription->getItsOnlyUnpaidInitInvoice();

            return redirect()->action('SubscriptionController@payment', [
                'invoice_uid' => $invoice->uid,
            ]);
        }

        // 3. SUBSCRIPTION IS ACTIVE, SHOW DETAILS PAGE
        return view('subscription.index', [
            'subscription' => $subscription,
            'plan' => $subscription->plan,
        ]);
    }
    
    public function init(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;
        $plan = Plan::findByUid($request->plan_uid);

        // create new subscription
        $subscription = $customer->assignPlan($plan);

        // create init invoice
        if (!$subscription->invoices()->new()->count()) {
            $subscription->createInitInvoice();
        }

        // Check if subscriotion is new
        return redirect()->action('SubscriptionController@billingInformation');
    }

    public function payment(Request $request)
    {
        // Get current customer
        $customer = $request->user()->customer;

        // get invoice
        $invoice = $customer->invoices()->where('uid', '=', $request->invoice_uid)->first();

        if (!$invoice || !$invoice->isNew()) {
            return redirect()->action('SubscriptionController@index');
        }

        if ($invoice->isNew()) {
            if ($invoice->getPendingTransaction()) {
                return view('subscription.pending', [
                    'invoice' => $invoice,
                ]);
            } else {
                if ($invoice->isFree()) {
                    return view('subscription.confirmFree', [
                        'invoice' => $invoice,
                    ]);
                } else {
                    return view('subscription.payment', [
                        'invoice' => $invoice,
                    ]);
                }
            }
        }
    }

    public function confirmFree(Request $request)
    {
        // get invoice
        $invoice = $request->user()->customer->invoices()->where('uid', '=', $request->invoice_uid)->first();

        if (!$invoice->isFree()) {
            throw new \Exception('Invoice is not free!');
        }
        
        $invoice->confirmWithoutPayment();

        $request->session()->flash('alert-success', trans('messages.invoice.confirmed'));
        return redirect()->action('SubscriptionController@index');
    }

    public function cancelInvoice(Request $request, $uid)
    {
        $invoice = \Acelle\Model\Invoice::findByUid($uid);

        if (!$request->user()->customer->can('delete', $invoice)) {
            return $this->notAuthorized();
        }

        $invoice->cancel();

        // Redirect to my subscription page
        $request->session()->flash('alert-success', trans('messages.invoice.cancelled'));
        return redirect()->action('SubscriptionController@index');
    }
    
    public function checkout(Request $request)
    {
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $invoice = $subscription->getUnpaidInvoice();

        $request->user()->customer->updatePaymentMethod([
            'method' => $request->payment_method,
        ]);

        // redirect to service checkout
        return redirect()->away($customer->getPreferredPaymentGateway()->getCheckoutUrl($invoice));
    }

    public function billingInformation(Request $request)
    {
        $customer = $request->user()->customer;
        $subscription = $customer->subscription;
        $invoice = $subscription->getUnpaidInvoice();
        $billingAddress = $customer->getDefaultBillingAddress();

        // has no address yet
        if (!$billingAddress) {
            $billingAddress = $customer->newBillingAddress();
        }

        // copy from contacy
        if ($request->same_as_contact == 'true') {
            $billingAddress->copyFromContact();
        }

        // Save posted data
        if ($request->isMethod('post')) {
            list($validator, $billingAddress) = $billingAddress->updateAll($request);
            
            // redirect if fails
            if ($validator->fails()) {
                return response()->view('subscription.billingInformation', [
                    'billingAddress' => $billingAddress,
                    'invoice' => $invoice,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $request->session()->flash('alert-success', trans('messages.billing_address.updated'));

            // return to subscription
            return redirect()->action('SubscriptionController@payment', [
                'invoice_uid' => $invoice->uid,
            ]);
        }

        return view('subscription.billingInformation', [
            'billingAddress' => $billingAddress,
            'invoice' => $invoice,
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
        $gateway = $customer->getPreferredPaymentGateway();
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
                $changePlanInvoice = $subscription->createChangePlanInvoice($newPlan);
            } catch (\Exception $e) {
                $request->session()->flash('alert-error', $e->getMessage());
                return redirect()->action('SubscriptionController@index');
            }

            // return to subscription
            return redirect()->action('SubscriptionController@payment', [
                'invoice_uid' => $changePlanInvoice->uid,
            ]);
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
        return redirect()->action('SubscriptionController@index');
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
        return redirect()->action('SubscriptionController@index');
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
        return redirect()->action('SubscriptionController@index');
    }
}
