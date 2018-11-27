@if (is_object($subscription->plan))
    <div class="row">
        <div class="col-md-6 plan-left">
            <ul class="dotted-list topborder section mb-0">
                <li class="border-bottom-0">
                    <div class="unit size1of2 text-bold">
                        <strong>{{ trans('messages.price') }}</strong>
                    </div>
                    <div class="lastUnit size1of2">
                        <h5 class="mt-0 mb-0 text-semibold">
                            <mc:flag>
                                @if ($subscription->price)
                                    {{ Acelle\Library\Tool::format_price($subscription->price, $subscription->currency_format) }}
                                @else
                                    {{ trans('messages.free') }}
                                @endif
                            </mc:flag>
                        </h5>
                    </div>
                </li>
            </ul>

            @include('subscriptions._details', ['subscription' => $subscription])
           
        </div>
    </div>
        
    @if ($subscription->isFree())
        <div class="text-left">
            <a link-method="POST" href="{{ action('AccountSubscriptionController@process', ['plan_uid' => $subscription->plan->uid]) }}" class="btn bg-teal">
            {{ trans('messages.get_started') }}
            <i class="icon-arrow-right7"></i></a>
        </div>
    @else
        <h3>{{ trans('messages.subscription.select_payment') }}</h3>
            
        <p>{{ trans('messages.subscription.select_payment.wording') }}</p>
            
        <div class="list-boxes">
            @foreach ($gateways as $name => $gateway)
                <div class="list-box {{ trans('messages.payment.status.' . $gateway['status']) }}">
                    <a link-method="POST" href="{{ action('AccountSubscriptionController@process', ['plan_uid' => $subscription->plan->uid, 'gateway' => $name]) }}">
                        <label>{{ trans('messages.payments.' . $name) }}</label>
                        <p>{{ trans('messages.payments.' . $name . '.list_intro') }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endif
