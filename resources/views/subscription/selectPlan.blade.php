@extends('emotsy.layouts.frontend_clean', [
    'subscriptionPage' => true,
])

@section('title', trans('messages.subscriptions'))

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('menu_title')
    @include('subscription._title')
@endsection

@section('menu_right')
    @include('layouts.core._top_activity_log')
    @include('layouts.core._menu_frontend_user', [
        'menu' => 'subscription',
    ])
@endsection

@section('content')    
    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">{{ trans('messages.subscription.choose_a_plan') }}</h2>
                @if ($getLastCancelledOrEndedGeneralSubscription)
                    @include('elements._notification', [
                        'level' => 'warning',
                        'message' => trans('messages.subscription.ended_intro', [
                            'ended_at' => Auth::user()->customer->formatDateTime($getLastCancelledOrEndedGeneralSubscription->current_period_ends_at, 'datetime_full'),
                            'plan' => $getLastCancelledOrEndedGeneralSubscription->planGeneral->name,
                        ])
                    ])
                @endif

                @include('elements._notification', [
                    'level' => 'warning',
                    'message' => trans('messages.no_plan.title')
                ])
    
                <p class="text-muted2 mt-4 mb-5">{{ trans('messages.select_plan.wording') }}</p>
                
                @if (empty($planGroups))
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
                        <div class="row" style="padding-left:20px;padding-right:20px;">

                            @foreach ($planGroups as $key => $planGroup)
                                
                                <div
                                    class="new-price-item mb-3 d-inline-block plan-item select-plan-item"
                                    data-control="plan"
                                >
                                    <div class="top-info">
                                        <div class="d-flex align-items-center mb-4">
                                            <h2 class="mb-0 mt-1 fw-semibold me-4 plan-group-label">{{ $planGroup->name }}</h2>
                                            <div>
                                                <p class="mt-0 mb-0 small">{{ $planGroup->description }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex align-items-center select-tier-box"  style="
                                            border: solid 1px #5AC4DF;  
                                            padding: 0px 20px 0px 16px;  
                                            border-radius: 40px;
                                        ">
                                            <label for="" class="small mb-0 fw-semibold me-3 text-nowrap">{{ trans('messages.plan_group.select_tier') }}:</label>
                                            <select data-control="tier-select" name="select_tier" class="select w-100"
                                                style=""
                                            >
                                                @foreach ($planGroup->plans()->general()->active()->available()->get() as $key => $plan)
                                                    <option value="{{ $plan->uid }}">{{ $plan->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div data-control="tier-info">
                                            
                                        </div>
                                    </div>

                                    <div data-control="tier-details" class="tier-details">
                                            
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        var SubscriptionSelectPlan = {
        }

        $(function() {
            var manager = new GroupManager();
            $('.plan-item').each(function() {
                manager.add({
                    box: $(this),
                    url: $(this).attr('data-url')
                });
            });

            manager.bind(function(group, others) {
                group.box.on('click', function() {
                    group.box.addClass('current');

                    others.forEach(function(other) {
                        other.box.removeClass('current');
                    });

                    // load order
                    // SubscriptionSelectPlan.getOrderBox().load(group.url);
                })
            });

            // TierSelector
            $('[data-control="plan"]').each(function(e) {
                new TierSelector({
                    url: '{{ action('SubscriptionController@selectTier') }}',
                    selector: $(this).find('[data-control="tier-select"]'),
                    tierInfo: $(this).find('[data-control="tier-info"]'),
                    tierDetails: $(this).find('[data-control="tier-details"]'),
                }); 
            });
                
        });

        var TierSelector = class {
            constructor(options) {
                this.url = options.url;
                this.selector = options.selector;
                this.tierInfo = options.tierInfo;
                this.tierDetails = options.tierDetails;

                // 
                this.load();

                // event
                this.events();
            }

            events() {
                var _this = this;

                this.selector.on('change', function() {
                    _this.load();
                });
            }

            getTierUid() {
                return this.selector.val();
            }

            load() {
                var _this = this;

                $.ajax({
                    method: "GET",
                    url: this.url,
                    data: {
                        plan_uid: this.getTierUid(),
                    }
                })
                .done(function( response ) {
                    _this.tierInfo.html($('<div>').html(response).find('[data-control="tier-info"]').html());
                    _this.tierDetails.html($('<div>').html(response).find('[data-control="tier-details"]').html());

                    initJs(_this.tierInfo);
                    initJs(_this.tierDetails);
                });
            }
        }
    </script>
@endsection