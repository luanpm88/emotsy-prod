<p class="alert alert-warning">
    {!! trans('messages.subscription.future_pending.' . \Acelle\Model\Setting::get('system.payment_gateway')) !!}
    <br/>
</p>
<p class="alert alert-info mb-40">
        {!! $gateway->notice !!}
</p>
    
