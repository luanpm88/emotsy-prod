<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Setting;
use Illuminate\Support\MessageBag;

class PaymentController extends Controller
{
    /**
     * Display all paymentt.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MessageBag $message_bag)
    {
        return view('admin.payments.index', [
            'gateways' => Setting::getPaymentGateways(),
        ]);
    }
    
    /**
     * Editing payment gateways.
     *
     * @param int $name
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        $gateway = Setting::getPaymentGateway($name);
        
        return view('admin.payments.edit', [
            'gateway' => $gateway,
        ]);
    }
    
    /**
     * Update payment gateway.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $name
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MessageBag $message_bag, $name)
    {
        $errors = new MessageBag;
        $gatewayService = Setting::getPaymentGatewayService($name, $request->options);
        
        try {
            $gatewayService->valid();            
            Setting::updatePaymentGateway($name, $request->options);
            
            $request->session()->flash('alert-success', trans('messages.payment_gateway.updated'));
            return redirect()->action('Admin\PaymentController@index');
        } catch (\Exception $ex) {
            // Add example error messages to the MessageBag instance.
            $errors->add('payment',
                trans('messages.payment_gateway.not_valid', ['message' => $ex->getMessage()]));
            
            return view('admin.payments.edit', [
                'gateway' => Setting::getPaymentGateway($name),
                'errors' => $errors,
            ]);
        }
    }
    
    /**
     * Set payment gateway as primary.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $name
     *
     * @return \Illuminate\Http\Response
     */
    public function setPrimary(Request $request, $name)
    {
        $service = Setting::getPaymentGatewayService($name);
        
        try {
            $service->valid();
            
            Setting::set('system.payment_gateway', $name);
            
            $request->session()->flash('alert-success', trans('messages.payment_gateway.updated'));
            return redirect()->action('Admin\PaymentController@index');
        } catch (\Exception $ex) {            
            $request->session()->flash('alert-error', trans('messages.payment_gateway.not_valid', ['message' => $ex->getMessage()]));
            return redirect()->action('Admin\PaymentController@edit', $name);
        }
    }
}
