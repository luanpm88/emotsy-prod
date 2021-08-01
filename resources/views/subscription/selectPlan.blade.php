@extends('layouts.core.dark')

@section('title', trans('messages.subscriptions'))

@section('menu_title')
    @include('subscription._title')
@endsection

@section('menu_right')
    @include('layouts.core._top_activity_log')
    @include('layouts.core._menu_frontend_user')
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card subscription-step">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">1</label></div>
                            <div>
                                <h5 class="fw-600 mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.choose_plan.title') }}
                                </h5>
                                <p class="m-0 text-muted">{{ trans('messages.subscription.choose_plan.subtitle') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-4" style="padding-left: 72px">
                        @if (empty($plans))
                            <div class="row">
                                <div class="col-md-6">
                                    @include('elements._notification', [
                                        'level' => 'danger',
                                        'message' => trans('messages.plan.no_available_plan')
                                    ])
                                </div>
                            </div>
                        @else
                            <div class="new-price-box" style="margin-right: -30px">
                                <div class="">

                                    @foreach ($plans as $key => $plan)
                                        <div class="new-price-item mb-3 d-inline-block" style="width: calc(33% - 20px)">
                                            <div style="height: 100px">
                                                <div class="price">
                                                    {!! format_price($plan->price, $plan->currency->format, true) !!}
                                                    <span class="p-currency-code">{{ $plan->currency->code }}</span>
                                                </div>
                                                <p><span class="material-icons-outlined text-muted2">
                                                    restore
                                                    </span> {{ $plan->displayFrequencyTime() }}</p>
                                            </div>
                                            <hr class="mb-2" style="width: 40px">
                                            <div style="height: 40px">
                                                <label class="plan-title fs-5 fw-600 mt-0">{{ $plan->name }}</label>
                                            </div>

                                            <div style="height: 130px">
                                                <p class="mt-4">{{ $plan->description }}</p>
                                            </div>

                                            <span class="time-box d-block text-center small py-2 fw-600">
                                                {{ trans('messages.plan.starting_at') }}
                                                {!! format_price($plan->price, $plan->currency->format) !!} <br>
                                                {{ $plan->displayFrequencyTime() }}
                                            </span>

                                            <a
                                                link-method="POST"
                                                href="{{ action('SubscriptionController@init', ['plan_uid' => $plan->uid]) }}"
                                                class="btn btn-primary rounded-3 d-block mt-4 shadow-sm">
                                                    {{ trans('messages.plan.select') }}
                                            </a>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @include('subscription._billingInformation')

                @include('subscription._paymentMethod')
            </div>
            <div class="col-md-4">
                <div class="card mt-2">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="fw-600 mb-0 fs-6 text-start">
                                    Your Order
                                </h5>
                            </div>
                        </div>
                            
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <p>{{ trans('messages.suscription.order_empty') }}

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection