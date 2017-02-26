@if (is_object($subscription->plan))
    <div class="row">        
        <div class="col-md-6 plan-left">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">									
                        <label>{{ trans('messages.end_at') }}</label>
                        <div class="text-left">											
                            <h3 class="mt-0 text-bold">{{
                            $subscription->start_at ? \Acelle\Library\Tool::dateTime($subscription->start_at)->format(trans('messages.date_format')) : '' }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">									
                        <label>{{ trans('messages.end_at') }}</label>
                        <div class="text-left">											
                            <h3 class="mt-0 text-bold">{{
                            $subscription->end_at ? \Acelle\Library\Tool::dateTime($subscription->end_at)->format(trans('messages.date_format')) : '' }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">									
                        <label>{{ trans('messages.subscription_time_registered') }}</label>
                        <div class="text-left">											
                            <h3 class="mt-0 text-bold">
                            {{ $subscription->plan->displayFrequencyTime() }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">									
                        <label>{{ trans('messages.total') }}</label>
                        <div class="text-left">											
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
            <hr />
            
            @if ($subscription->isFree())
                <div class="text-left">
                    <button type='submit' class="btn bg-teal">
                    {{ trans('messages.get_started') }}
                    <i class="icon-arrow-right7"></i></button>
                </div>
            @else
                <div class="text-left">
                    <button type='submit' class="btn bg-teal">
                    {{ trans('messages.checkout') }}
                    <i class="icon-arrow-right7"></i></button>
                </div>
            @endif
        </div>
        <div class="col-md-6 plan-right text-center plan-top-80">
            @include('admin/plans/_plan', [
                'plan' => $subscription->plan,
                'readonly' => true,
            ])
        </div>
    </div>
@endif