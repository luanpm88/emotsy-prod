@extends('layouts.popup.full')

@section('content')
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <div class="sub-section">
                
                    <h2>{{ trans('messages.subscription.select_a_plan') }}</h2>
                    <p>{{ trans('messages.subscription.change_plan.select_below') }}</p>

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
                                        <h2 class="mb-3 mt-1 fw-semibold" style='
                                            font-family: "Poppins", "Open Sans","Helvetica Neue",Arial,Helvetica,Verdana,sans-serif
                                        '>{{ $planGroup->name }}</h2>
                                        <label for="" class="small mb-1 fw-semibold">{{ trans('messages.plan_group.select_tier') }}</label>
                                        <select data-control="tier-select" name="select_tier" class="select w-100">
                                            @foreach ($planGroup->plans()->general()->active()->available()->get() as $key => $plan)
                                                <option value="{{ $plan->uid }}">{{ $plan->name }}</option>
                                            @endforeach
                                        </select>

                                        <div  data-control="tier-details">
                                            
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endif

                    <script>
                        $(function() {
                            // TierSelector
                            $('[data-control="plan"]').each(function(e) {
                                new TierSelector({
                                    url: '{{ action('SubscriptionController@selectTierChange') }}',
                                    selector: $(this).find('[data-control="tier-select"]'),
                                    content: $(this).find('[data-control="tier-details"]'),
                                }); 
                            });
                                
                        });
                
                        var TierSelector = class {
                            constructor(options) {
                                this.url = options.url;
                                this.selector = options.selector;
                                this.content = options.content;
                
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
                                    _this.content.html(response);
                
                                    initJs(_this.content);
                                });
                            }
                        }
                    </script>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>

    <script>
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
                })
            });
        });
    </script>
@endsection