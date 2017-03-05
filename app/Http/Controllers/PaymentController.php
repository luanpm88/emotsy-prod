<?php
namespace Acelle\Http\Controllers;
use Illuminate\Http\Request;
use Acelle\Model\Payment;

class PaymentController extends Controller {
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth', ['except' => [
            'avatar'
        ]]);
    }
    
    /**
     * Subscription pay by PayPal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paypal(Request $request, $subscription_uid) {
        $subscription = \Acelle\Model\Subscription::findByUid($subscription_uid);
        
        return view('payments.paypal', [
            'subscription' => $subscription,
        ]);
    }
    
    /**
     * Subscription pay by Braintree credit card.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function braintree_credit_card(Request $request, $subscription_uid) {
        $subscription = \Acelle\Model\Subscription::findByUid($subscription_uid);
        $result = NULL;
        
        $payment_method = \Acelle\Model\PaymentMethod::getByType(\Acelle\Model\PaymentMethod::TYPE_BRAINTREE_CREDIT_CARD);        
        $payment_method->setAuth();        
        
        $clientToken = \Braintree_ClientToken::generate();
        
        $order_id = $subscription->getOrderID();
        
        // validate and save posted data
        if ($request->isMethod('post')) {
            $nonceFromTheClient = $request->payment_method_nonce;
            
            $result = \Braintree_Transaction::sale([
                'amount' => $subscription->price,
                'paymentMethodNonce' => $nonceFromTheClient,
                "orderId" => $order_id,
                'options' => [
                  'submitForSettlement' => True
                ]
            ]);
            
            $payment = new Payment();
            $payment->subscription_id = $subscription->id;
            $payment->payment_method_id = $payment_method->id;
            $payment->data = serialize($result);
            $payment->status = $result->success ? 'success' : 'failed';
            $payment->payment_method_name = $payment_method->name;
            $payment->order_id = $order_id;
            $payment->save();

            if ($result->success) {
                $subscription->setActive();
                $subscription->setPaid();
                
                return redirect()->action('PaymentController@success', $subscription->uid);
            }
        }
        
        return view('payments.braintree_credit_card', [
            'subscription' => $subscription,
            'clientToken' => $clientToken,
            'result' => $result,
        ]);
    }
    
    /**
     * Subscription pay by Braintree credit card.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function braintree_paypal(Request $request, $subscription_uid) {
        $subscription = \Acelle\Model\Subscription::findByUid($subscription_uid);
        $result = NULL;
        
        $payment_method = \Acelle\Model\PaymentMethod::getByType(\Acelle\Model\PaymentMethod::TYPE_BRAINTREE_PAYPAL);        
        $payment_method->setAuth();   
        
        $clientToken = \Braintree_ClientToken::generate();
        
        $order_id = $subscription->getOrderID();
        
        // validate and save posted data
        if ($request->isMethod('post')) {
            $nonceFromTheClient = $request->payment_method_nonce;
            
            $result = \Braintree_Transaction::sale([
                "amount" => $subscription->price,
                "paymentMethodNonce" => $nonceFromTheClient,
                "orderId" => $order_id,
                'options' => [
                  'submitForSettlement' => True
                ]
            ]);            
            
            $payment = new Payment();
            $payment->subscription_id = $subscription->id;
            $payment->data = serialize($result);
            $payment->payment_method_id = $payment_method->id;
            $payment->status = $result->success ? 'success' : 'failed';
            $payment->payment_method_name = $payment_method->name;
            $payment->order_id = $order_id;
            $payment->save();

            if ($result->success) {
                $subscription->setActive();
                $subscription->setPaid();
                
                return redirect()->action('PaymentController@success', $subscription->uid);
            }
        }
        
        return view('payments.braintree_paypal', [
            'subscription' => $subscription,
            'clientToken' => $clientToken,
            'result' => $result,
        ]);
    }
    
    /**
     * Payment success page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request, $subscription_uid) {
        $subscription = \Acelle\Model\Subscription::findByUid($subscription_uid);
        
        return view('payments.success', [
            'subscription' => $subscription
        ]);
    }
    
    /**
     * Subscription update paid status from Service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paymentStatus(Request $request) {        
        if($request->tx) {
            if($payment=Payment::where('transaction_id',$request->tx)->first()){
                $payment_id=$payment->id;
            }else{
                $payment=new Payment;
                $payment->item_number = $request->item_number;
                $payment->transaction_id = $request->tx;
                $payment->currency_code = $request->cc;
                $payment->payment_status = $request->st;
                $payment->save();
                
                $payment_id=$payment->id;
            }
            
            return 'Pyament has been done and your payment id is : '.$payment_id;        
        }else{
            return 'Payment has failed';
        }
    }
    
    /**
     * Subscription pay bay cash.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cash(Request $request, $subscription_uid) {
        $request->session()->flash('alert-success', trans('messages.subscription.cash.created'));
        return redirect()->action('SubscriptionController@index');
    }
}