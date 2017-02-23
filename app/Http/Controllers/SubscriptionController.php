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
        // Subscription        
        $subscription = new \Acelle\Model\Subscription();        
        if($request->plan_uid) {
            $plan = \Acelle\Model\Plan::findbyUid($request->plan_uid);
            $subscription->plan_id = $plan->id;            
        }
        
        $subscription->fillAttributes($request->all());
        if (!empty($request->old())) {
            $subscription->fillAttributes($request->old());
        }
        
        // Customer account
        $customer = new \Acelle\Model\Customer();
        $customer->uid = 0;
        $customer->status = \Acelle\Model\Customer::STATUS_ACTIVE;
        if (!empty($request->old())) {
            $customer->fill($request->old());
            // User info
            $customer->user = new \Acelle\Model\User();
            $customer->user->fill($request->old());
        }
        
         // save posted data
        if ($request->isMethod('post')) {
            // Validation
            $this->validate($request, array_merge($customer->rules(), $subscription->frontendRules()));
            
            // Save customer            
            $customer->updateInformation($request);
            
            // Save current user info
            $subscription->fillAttributes($request->all());            
            $subscription->customer_id = $customer->id;
            $subscription->save();
            
            // Send registration confirmation email
            $user = \Acelle\Model\User::find($customer->user_id);
            $user->sendActivationMail($customer->displayName(), action('SubscriptionController@checkout'));
            
            return view('subscriptions.register_confirmation_notice');
        }
        
        return view('subscriptions.register', [
            'subscription' => $subscription,
            'customer' => $customer
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
     * Checkout page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $customer = $request->user()->customer;
        $subscription = $customer->getCurrentSubscription();
        
        return view('subscriptions.checkout', [
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

        return view('admin.subscriptions._list', [
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
}
