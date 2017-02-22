<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\Subscription())) {
            return $this->notAuthorized();
        }

        $subscriptions = \Acelle\Model\Subscription::getAll();

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
        if (\Gate::denies('read', new \Acelle\Model\Subscription())) {
            return $this->notAuthorized();
        }

        $subscriptions = \Acelle\Model\Subscription::search($request)->paginate($request->per_page);

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

        return view('admin.subscriptions.create', [
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
        $subscription = new \Acelle\Model\Subscription();

        // authorize
        if (\Gate::denies('create', $subscription)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, $subscription->rules());
            
            $plan = \Acelle\Model\Plan::find($request->plan_id);
            $subscription->fillAttributes($request->all());
            $subscription->options = $plan->options;
            $subscription->price = $plan->price;
            $subscription->save();
            
            $request->session()->flash('alert-success', trans('messages.subscription.created'));
            return redirect()->action('Admin\SubscriptionController@index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            var_dump($request->old());
        }

        return view('admin.subscriptions.edit', [
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
        $subscription = \Acelle\Model\Subscription::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $subscription)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, $subscription->rules());
            
            $plan = \Acelle\Model\Plan::find($request->plan_id);
            $subscription->fillAttributes($request->all());
            $subscription->options = $plan->options;
            $subscription->price = $plan->price;
            $subscription->save();
            
            $request->session()->flash('alert-success', trans('messages.subscription.updated'));
            return redirect()->action('Admin\SubscriptionController@index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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

        // authorize
        if (\Gate::denies('read', $subscription)) {
            return $this->notAuthorized();
        }

        $subscription->fillAttributes($request->all());
        
        return view('admin.subscriptions.preview', [
            'subscription' => $subscription
        ]);
    }
}
