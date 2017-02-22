@if (is_object($subscription->plan))
    <div class="row">
        <div class="col-md-12 plan-right">
            @include('admin/plans/_plan', [
                'plan' => $subscription->plan,
                'readonly' => true,
            ])
        </div>
        <div class="col-md-12 plan-left">            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">									
                        <label>{{ trans('messages.subscription_time_registered') }}</label>
                        <div class="text-right">											
                            <h3 class="mt-0 text-bold">{{
                            trans('messages.display_' . $subscription->plan->frequency_unit . '_number', [
                                'number' => $subscription->quantity * $subscription->plan->frequency_amount
                            ]) }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">									
                        <label>{{ trans('messages.total') }}</label>
                        <div class="text-right">											
                            <h3 class="mt-0 text-bold">
                                @if ($subscription->price)
                                    {{ Acelle\Library\Tool::format_price($subscription->price, $subscription->plan->currency->format) }}
                                @else
                                    {{ trans('messages.free') }}
                                @endif
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
@endif