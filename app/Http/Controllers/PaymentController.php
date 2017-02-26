<?php
namespace Acelle\Http\Controllers;
use Illuminate\Http\Request;
use Acelle\Model\Payment;

class PaymentController extends Controller {

    public function payment(Request $request, $subscription_uid) {
        $subscription = \Acelle\Model\Subscription::findByUid($subscription_uid);
        
        return view('payments.payment', [
            'subscription' => $subscription,
        ]);
    }

    public function paymentInfo(Request $request) {        
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
}