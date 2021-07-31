@extends('layouts.core.dark')

@section('title', trans('messages.subscriptions'))

@section('menu_title')
    @include('subscription._title')
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-md-9">
                @if ($subscription)
                    @include('elements._notification', [
                        'level' => 'warning',
                        'message' => trans('messages.subscription.ended_intro', [
                            'ended_at' => Acelle\Library\Tool::formatDate($subscription->ends_at),
                            'plan' => $subscription->plan->name,
                        ])
                    ])
                    
                    <p>{{ trans('messages.select_plan.wording') }}</p>
                @else
                    @include('elements._notification', [
                        'level' => 'warning',
                        'message' => trans('messages.no_plan.title')
                    ])
                @endif

                <div class="card">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">1</label></div>
                            <div>
                                <h5 class="fw-bold mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.choose_plan.title') }}
                                </h5>
                                <p class="m-0">{{ trans('messages.subscription.choose_plan.subtitle') }}</p>
                            </div>
                        </div>
                            
                    </div>
                    <div class="card-body">
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
                            <div class="new-price-box">
                                <div class="d-flex">

                                    @foreach ($plans as $plan)
                                        <div class="new-price-item">
                                            <div style="height: 100px">
                                                <div class="price">
                                                    {!! format_price($plan->price, $plan->currency->format, true) !!}
                                                    <span class="p-currency-code">{{ $plan->currency->code }}</span>
                                                </div>
                                                <div>{{ $plan->displayFrequencyTime() }}</div>
                                            </div>
                                            <hr class="mb-2" style="width: 50px">
                                            <div style="height: 50px">
                                                <label class="plan-title fs-5 fw-600 mt-0">{{ $plan->name }}</label>
                                            </div>

                                            <div style="height: 150px">
                                                <p class="mt-4">{{ $plan->description }}</p>
                                            </div>

                                            <span class="time-box d-block small">
                                                {{ trans('messages.plan.starting_at') }}
                                                {!! format_price($plan->price, $plan->currency->format) !!} <br>
                                                {{ $plan->displayFrequencyTime() }}
                                            </span>

                                            <a
                                                link-method="POST"
                                                href="{{ action('AccountSubscriptionController@init', ['plan_uid' => $plan->uid]) }}"
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

                <div class="card mt-2">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">2</label></div>
                            <div>
                                <h5 class="fw-bold mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.billing_information.title') }}
                                </h5>
                                <p class="m-0">{{ trans('messages.subscription.billing_information.subtitle') }}</p>
                            </div>
                        </div>
                            
                    </div>
                </div>

                <div class="card mt-2">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><label class="subscription-step-number">3</label></div>
                            <div>
                                <h5 class="fw-bold mb-0 fs-6 text-start">
                                    {{ trans('messages.subscription.payment_method.title') }}
                                </h5>
                                <p class="m-0">{{ trans('messages.subscription.payment_method.subtitle') }}</p>
                            </div>
                        </div>
                            
                    </div>
                </div>

                
            </div>
        </div>
    </div>
@endsection