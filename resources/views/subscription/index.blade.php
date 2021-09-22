@extends('layouts.core.frontend')

@section('title', trans('messages.subscription'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.subscription') }}</li>
        </ul>
        <h1>
            <span class="text-semibold">{{ Auth::user()->displayName() }}</span>
        </h1>
    </div>

@endsection

@section('content')

    @include("account._menu")

    <div class="row">
        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="notification_group">
                @if ($subscription->getItsOnlyUnpaidRenewInvoice())
                    @if (!\Auth::user()->customer->preferredPaymentGatewayCanAutoCharge())
                        @include('elements._notification', [
                            'level' => 'warning',
                            'message' => trans('messages.have_new_renew_invoice')
                        ])
                    @else
                        @include('elements._notification', [
                            'level' => 'warning',
                            'message' => trans('messages.have_new_renew_invoice.auto', [
                                'date' => \Acelle\Library\Tool::formatDateTime($subscription->getDueDate()),
                            ])
                        ])

                        @if ($subscription->getItsOnlyUnpaidRenewInvoice()->lastTransactionIsFailed())
                            @include('elements._notification', [
                                'level' => 'danger',
                                'message' => $subscription->getItsOnlyUnpaidRenewInvoice()->lastTransaction()->error
                            ])
                        @endif
                    @endif
                @endif

                @if ($subscription->getItsOnlyUnpaidChangePlanInvoice())
                    @include('elements._notification', [
                        'level' => 'warning',
                        'message' => trans('messages.have_new_change_plan_invoice', [
                            'link' => action('SubscriptionController@payment', [
                                'invoice_uid' => $subscription->getItsOnlyUnpaidChangePlanInvoice()->uid,
                            ]),
                        ])
                    ])
                @endif

                @if ($subscription->isTrial() && !$subscription->getUnpaidInvoice())
                    <div class="border shadow-sm py-4 px-4 rounded mb-4 bg-white">
                        <div class="d-flex px-2">
                            <div class="me-4">
                                <svg style="width:45px;height:45px" xmlns="http://www.w3.org/2000/svg" width="628.2" height="542" viewBox="0 0 628.2 542"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M60.5,133.5h-30a18.1,18.1,0,0,0-18,18v259c.7,25.1,21,45.3,45,46,25.5.8,47.8-20.3,48-47V38.3a25.7,25.7,0,0,1,25.8-25.8H508.6a25.9,25.9,0,0,1,25.9,25.9V258.5" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="336.5" y1="104" x2="452.3" y2="104" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="336.7" y1="158" x2="452.5" y2="158" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="175.5" y1="256" x2="288" y2="256" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="175.5" y1="316.5" x2="288" y2="316.5" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="175.5" y1="378" x2="288" y2="378" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="342" y1="256" x2="459" y2="256" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="342" y1="317" x2="342" y2="317" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="81" y1="451" x2="307" y2="451" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><path d="M575.8,450.2a108.3,108.3,0,0,0,4.2-30.9c-.5-59-48.6-107.4-107.7-108.3a109.3,109.3,0,1,0-1.5,218.5" style="fill:none;stroke:#059ce6;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><path d="M543.9,501.4c23.5-19.1,56.8-40.4,84.3-49.6l-49.7-9.2-32.3-38.9C551.6,432.1,549.2,471.6,543.9,501.4Z" style="fill:#059ce6"/><polygon points="266.2 172.2 226.6 156.8 191.5 180.7 193.9 138.3 160.3 112.3 201.4 101.5 215.7 61.5 238.7 97.2 281.2 98.5 254.3 131.4 266.2 172.2" style="fill:none;stroke:#e69305;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="476" y1="428" x2="476" y2="373" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/><line x1="418" y1="428" x2="476" y2="428" style="fill:none;stroke:#999;stroke-linecap:round;stroke-linejoin:round;stroke-width:25px"/></g></g></svg>
                            </div>
                            <p class="mb-0 me-auto pe-4">{!! trans('messages.subscription.trial_announcement', [
                                'plan' => $plan->name,
                                'days' => $subscription->trial_ends_at->diffInDays( \Carbon\Carbon::now() ),
                            ]) !!}</p>
                            <div class="ms-auto text-nowrap">
                                <a
                                    link-method="POST"
                                    href="{{ action('SubscriptionController@buyNow') }}"
                                    class="btn btn-secondary"
                                    data-size="sm"
                                >
                                    {{ trans('messages.subscription.buy_now') }}
                                </a>
                            </div>
                        </div>
                    </div>         
                @endif
            </div>

            <h2 class="text-semibold">{{ trans('messages.subscription') }}</h2>

            <div class="sub-section">
                @if ($subscription->isActive())
                    @if (!$subscription->cancelled())
                        <p>
                            {!! trans('messages.subscription.current_subscription.wording', [
                                'plan' => $plan->name,
                                'money' => Acelle\Library\Tool::format_price($plan->price, $plan->currency->format),
                                'remain' => $subscription->current_period_ends_at->diffForHumans(),
                                'next_on' => \Acelle\Library\Tool::formatDate($subscription->current_period_ends_at)
                            ]) !!}
                        </p>
                    @else
                        <p>
                            {!! trans('messages.subscription.current_subscription.cancel_at_end_of_period.wording', [
                                'plan' => $plan->name,
                                'money' => Acelle\Library\Tool::format_price($plan->price, $plan->currency->format),
                                'remain' => $subscription->current_period_ends_at->diffForHumans(),
                                'end_at' => \Acelle\Library\Tool::formatDate($subscription->current_period_ends_at)
                            ]) !!}
                        </p>
                    @endif                        

                    @if (\Auth::user()->customer->can('cancel', $subscription))
                        <a link-method="POST" link-confirm="{{ trans('messages.subscription.cancel.confirm') }}"
                            href="{{ action('SubscriptionController@cancel') }}"
                            class="btn btn-secondary me-1"
                        >
                            {{ trans('messages.subscription.cancel') }}
                        </a>
                    @endif

                    @if (\Auth::user()->customer->can('resume', $subscription))
                        <a link-method="POST" link-confirm="{{ trans('messages.subscription.resume.confirm') }}"
                            href="{{ action('SubscriptionController@resume') }}"
                            class="btn btn-secondary me-2"
                        >
                            {{ trans('messages.subscription.resume') }}
                        </a>
                    @endif

                    @if (\Auth::user()->customer->can('changePlan', $subscription))
                        <a
                            href="{{ action('SubscriptionController@changePlan', ["id" => $subscription->uid]) }}"
                            class="btn btn-default change_plan_button me-1"
                            data-size="sm"
                        >
                            {{ trans('messages.subscription.change_plan') }}
                        </a>
                    @endif

                    @if (\Auth::user()->customer->can('cancelNow', $subscription))
                        <a link-method="POST" link-confirm="{{ trans('messages.subscription.cancel_now.confirm') }}"
                            href="{{ action('SubscriptionController@cancelNow') }}"
                            class="btn btn-danger me-2"
                        >
                            {{ trans('messages.subscription.cancel_now') }}
                        </a>
                    @endif
                @endif
            </div>
            @include('subscription._invoices')

            <div class="sub-section">
                <div class="">
                    <div class="">
                        <h2 class="text-semibold">{{ trans('messages.plan_details') }} </h2>
                        <p>{{ trans('messages.plan_details.intro') }}</p>

                        @include('plans._details', ['plan' => $plan])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4">
            @include('invoices.bill', [
                'bill' => $subscription->getUpcomingBillingInfo(),
                'payButton' => true,
            ])

            <div class="mt-4">
                @include('account._payment_info', [
                    'redirect' => action('SubscriptionController@index'),
                ])
            </div>
        </div>
    </div>
    

    <script>
        var changePlanModal;

        $(function() {
            $('.change_plan_button').click(function(e) {
                e.preventDefault();

                var src = $(this).attr('href');

                changePlanModal = new Popup();
                changePlanModal.load(src);
            });

            // Dotted list more/less
            $(document).on('click', '.dotted-list > li.more a', function() {
                var box = $(this).parents('.dotted-list');

                box.find('li').removeClass('hide');
                $(this).parents('li').hide();
            });
        });
    </script>

@endsection
