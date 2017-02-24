@extends('layouts.subscription')

@section('title', trans('messages.checkout'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
    @include('subscriptions._steps')
    
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <h2 class="mt-0"><i class="icon-quill4"></i> {{ trans('messages.plan_information') }}</h2>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 plan-left">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">									
                                        <label>{{ trans('messages.quantity') }}</label>
                                        <div class="text-left">											
                                            <h3 class="mt-0 text-bold">
                                            {{ $subscription->quantity }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">									
                                        <label>{{ trans('messages.subscription_note') }}</label>
                                        <div class="text-left">											
                                            <p class="mt-0">
                                            {{ $subscription->note }}
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">									
                                        <label>{{ trans('messages.start_at') }}</label>
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
                        </div>
                        <div class="col-md-6 plan-right">
                            @include('admin/plans/_plan', [
                                'plan' => $subscription->plan,
                                'readonly' => true,
                            ])
                        </div>
                    </div>
                </div>
            </div>
                
            <br />
            @if ($subscription->isFree())
                <div class="text-center">
                    <a href="{{ action('SubscriptionController@finish') }}" class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.confirm') }}</a>
                </div>
            @endif
        </div>
    </div>
@endsection
