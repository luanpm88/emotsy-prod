<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
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
            $plan = \Acelle\Model\Plan::find($request->plan_id);
            $subscription->fillAttributes($request->all());
            $subscription->options = $plan->options;
            $subscription->price = $plan->price;
            $subscription->customer_id = $customer->id;
            $subscription->save();
            
            // Send resgistration confirmation email
            $customer->user->sendActivationMail();
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
}
