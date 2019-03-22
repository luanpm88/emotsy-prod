<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Cashier\Subscription;
use Acelle\Model\Plan;

class SubscriptionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (!$request->user()->admin->can('read', new Subscription())) {
            return $this->notAuthorized();
        }

        // If admin can view all subscriptions of their customer
        if (!$request->user()->admin->can('readAll', new Subscription())) {
            $request->merge(array("customer_admin_id" => $request->user()->admin->id));
        }
        $subscriptions = Subscription::all();

        return view('admin.subscriptions.index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        // authorize
        if (!$request->user()->admin->can('read', new Subscription())) {
            return $this->notAuthorized();
        }

        // If admin can view all subscriptions of their customer
        if (!$request->user()->admin->can('readAll', new Subscription())) {
            $request->merge(array("customer_admin_id" => $request->user()->admin->id));
        }
        
        $subscriptions = Subscription::select('subscriptions.*');
        
        if ($request->filters) {
            if (isset($request->filters["customer_uid"])) {
                $subscriptions = $subscriptions->where('user_id', $request->filters["customer_uid"]);
            }
            
            if (isset($request->filters["plan_uid"])) {
                $subscriptions = $subscriptions->where('plan_id', $request->filters["plan_uid"]);
            }
        }
        
        if (!empty($request->sort_order)) {
            $subscriptions = $subscriptions->orderBy($request->sort_order, $request->sort_direction);
        }
        
        $subscriptions = $subscriptions->paginate($request->per_page);

        return view('admin.subscriptions._list', [
            'subscriptions' => $subscriptions,
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
        // Get current subscription
        $subscription = Subscription::findByUid($request->id);
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');

        if ($request->user()->admin->can('cancel', $subscription)) {        
            $subscription->cancel($gateway);
        }
        
        echo trans('messages.subscription.cancelled');
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
        // Get current subscription
        $subscription = Subscription::findByUid($request->id);
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');

        if ($request->user()->admin->can('resume', $subscription)) {        
            $subscription->resume($gateway);
        }
        
        echo trans('messages.subscription.resumed');
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
        // Get current subscription
        $subscription = Subscription::findByUid($request->id);
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');

        if ($request->user()->admin->can('cancelNow', $subscription)) {        
            $subscription->cancelNow($gateway);
        }

        echo trans('messages.subscription.cancelled_now');
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
        // Get current subscription
        $subscription = Subscription::findByUid($request->id);
        $plan = Plan::findByUid($request->plan_uid);
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');

        if (! $request->user()->admin->can('changePlan', $subscription)) {        
            return $this->notAuthorized();
        }
        
        if ($request->isMethod('post')) {
            if ($request->user()->customer->can('changePlan', $subscription)) {
                $subscription->user->changePlan($plan, $gateway);
                
                // Redirect to my subscription page
                $request->session()->flash('alert-success', trans('messages.subscription.plan_changed'));
                return back();
            }
        }
        
        return view('admin.subscriptions.change_plan', [
            'subscription' => $subscription,
        ]);
    }
    
    /**
     * Subscription invoices.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function invoices(Request $request, $id)
    {
        // Get current subscription
        $subscription = Subscription::findByUid($request->id);
        $gateway = \App::make('Acelle\Cashier\PaymentGateway');
        
        return view('admin.subscriptions.invoices', [
            'subscription' => $subscription,
            'gateway' => $gateway,
        ]);
    }
}
