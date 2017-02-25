<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth', [
            'except' => [
                'selectPlan',
                'register',
                'preview',                
            ]
        ]);
    }
    
    /**
     * Select a plan.
     */
    public function selectPlan()
    {
        $plans = \Acelle\Model\Plan::getAllActive()->get();
        
        return view('subscriptions.select_plan', [
            'plans' => $plans
        ]);
    }
    
    /**
     * Customer registration.
     */
    public function register(Request $request)
    {
        // Customer account
        $user = $request->user();
        $is_customer_logged_in = is_object($user) && is_object($user->customer);
        if($is_customer_logged_in) {
            return redirect()->action('SubscriptionController@subscription', $request->plan_uid);
        } else {
            $customer = new \Acelle\Model\Customer();
            $customer->uid = 0;
            $customer->status = \Acelle\Model\Customer::STATUS_ACTIVE;
            if (!empty($request->old())) {
                $customer->fill($request->old());
                // User info
                $customer->user = new \Acelle\Model\User();
                $customer->user->fill($request->old());
            }
        }
        
         // save posted data
        if ($request->isMethod('post')) {
            // Validation
            if($is_customer_logged_in) {
            } else {
                $this->validate($request, array_merge($customer->rules()));
            }
            
            // Save customer            
            if(!$is_customer_logged_in) {
                $customer->updateInformation($request);
            }
            
            // Send registration confirmation email
            if($is_customer_logged_in) {
            } else {
                $user = \Acelle\Model\User::find($customer->user_id);
                $user->sendActivationMail($customer->displayName(), action('SubscriptionController@subscription', $request->plan_uid));
                
                return view('subscriptions.register_confirmation_notice');
            }
        }
        
        return view('subscriptions.register', [
            'customer' => $customer,
            'is_customer_logged_in' => $is_customer_logged_in
        ]);
    }
    
    /**
     * Customer subscription.
     */
    public function subscription(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;
        
        // Subscription        
        $subscription = new \Acelle\Model\Subscription();
        
        if($request->plan_uid) {
            $plan = \Acelle\Model\Plan::findByUid($request->plan_uid);
            $subscription->plan_id = $plan->id;
        }
        
        $subscription->fillAttributes($request->all());
        if (!empty($request->old())) {
            $subscription->fillAttributes($request->old());
        }
        
         // save posted data
        if ($request->isMethod('post')) {
            // Validation
            $this->validate($request, $subscription->frontendRules());
            
            // Save current user info
            $subscription->fillAttributes($request->all());            
            $subscription->customer_id = $customer->id;
            $subscription->save();
            
            if ($subscription->isFree()) {
                $subscription->status = \Acelle\Model\Subscription::STATUS_ACTIVE;
                
                $request->session()->flash('alert-success', trans('messages.subscription.created'));
                return redirect()->action('SubscriptionController@finish');
            } else {
            }
        }
        
        return view('subscriptions.subscription', [
            'subscription' => $subscription
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
        // Get current user
        $subscription = new \Acelle\Model\Subscription();

        $subscription->fillAttributes($request->all());
        
        return view('subscriptions.preview', [
            'subscription' => $subscription
        ]);
    }
    
    /**
     * Finish page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finish(Request $request)
    {
        $customer = $request->user()->customer;
        $subscription = $customer->getCurrentSubscription();
        
        return view('subscriptions.finish', [
            'subscription' => $subscription
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer = $request->user()->customer;

        $subscriptions = \Acelle\Model\Subscription::search($request, $customer->id);

        return view('subscriptions.index', [
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
        $customer = $request->user()->customer;
        
        $subscriptions = \Acelle\Model\Subscription::search($request, $customer->id)->paginate($request->per_page);

        return view('subscriptions._list', [
            'subscriptions' => $subscriptions,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $subscription = new \Acelle\Model\Subscription();        

        // authorize
        if (\Gate::denies('create', $subscription)) {
            return $this->notAuthorized();
        }
        
        if (!empty($request->old())) {
            $subscription->fillAttributes($request->old());
        }

        return view('subscriptions.create', [
            'subscription' => $subscription
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get current user
        $user = $request->user();
        $customer = $user->customer;
        $subscription = new \Acelle\Model\Subscription();
        $subscription->status = \Acelle\Model\Subscription::STATUS_INACTIVE;

        // authorize
        if (\Gate::denies('create', $subscription)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, $subscription->frontendRules());
            
            $subscription->fillAttributes($request->all());            
            $subscription->customer_id = $customer->id;
            $subscription->save();
            
            $request->session()->flash('alert-success', trans('messages.subscription.created'));
            return redirect()->action('SubscriptionController@index');
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $uid)
    {
        $subscription = \Acelle\Model\Subscription::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $subscription)) {
            return $this->notAuthorized();
        }
        
        if (!empty($request->old())) {
            $subscription->fillAttributes($request->old());
        }

        return view('subscriptions.edit', [
            'subscription' => $subscription
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uid)
    {
        // Get current user
        $user = $request->user();
        $customer = $user->customer;
        
        $subscription = \Acelle\Model\Subscription::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $subscription)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, $subscription->frontendRules());
            
            $subscription->fillAttributes($request->all());            
            $subscription->customer_id = $customer->id;
            $subscription->save();
            
            $request->session()->flash('alert-success', trans('messages.subscription.updated'));
            return redirect()->action('SubscriptionController@index');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $subscriptions = \Acelle\Model\Subscription::whereIn('uid', explode(',', $request->uids));

        foreach ($subscriptions->get() as $subscription) {
            // authorize
            if (\Gate::allows('delete', $subscription)) {
                $subscription->delete();
            }
        }

        // Redirect to my lists page
        echo trans('messages.subscriptions.deleted');
    }
}
