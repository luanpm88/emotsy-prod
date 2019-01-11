@if (isset($plan))
    <div class="row">
        <div class="col-md-6 plan-left">
            @include('plans._details', ['plan' => $plan])
        </div>
    </div>
        
    @if ($plan->isFree())
        <div class="text-left">
            <a link-method="POST" href="{{ action('AccountSubscriptionController@process', ['plan_uid' => $plan->uid]) }}" class="btn bg-teal">
            {{ trans('messages.get_started') }}
            <i class="icon-arrow-right7"></i></a>
        </div>
    @else
        <h3>{{ trans('messages.subscription.select_payment') }}</h3>
            
        <p>{{ trans('messages.subscription.select_payment.wording') }}</p>
            
        <div class="list-boxes">
            @foreach ($gateways as $name => $gateway)
                <div class="list-box {{ trans('messages.payment.status.' . $gateway['status']) }}">
                    <a link-method="POST" href="{{ action('AccountSubscriptionController@process', ['plan_uid' => $plan->uid, 'gateway' => $name]) }}">
                        <label>{{ trans('messages.payments.' . $name) }}</label>
                        <p>{{ trans('messages.payments.' . $name . '.list_intro') }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endif
