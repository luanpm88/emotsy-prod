<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <h2 class="text-semibold">{{ trans('messages.subscription.top_up') }}</h2>
    
        <div class="sub-section">                
            <p>
                {!! trans('messages.subscription.top_up.wording', [
                    'plan' => $plan->name,
                    'remain' => $currentPeriodEnd->diffForHumans(),
                    'on' => \Acelle\Library\Tool::formatDate($currentPeriodEnd),
                ]) !!}
            </p>
                
            <a class="btn btn-mc_primary"
                href="{{ action('AccountSubscriptionController@topUp') }}">
                {{ trans('messages.subscription.payment.pay_top_up') }}
            </a>
        </div>
    </div>
</div>