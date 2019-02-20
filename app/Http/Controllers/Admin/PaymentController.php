<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Setting;

class PaymentController extends Controller
{
    /**
     * Display all paymentt.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
    public function update(Request $request, $name)
    {
        Setting::updatePaymentGateway($name, $request->options);
        
        $request->session()->flash('alert-success', trans('messages.payment_gateway.updated'));
        return redirect()->action('Admin\PaymentController@index');
    }
}
