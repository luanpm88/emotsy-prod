@extends('layouts.popup.medium')

@section('title')
  {{ trans('messages.plan_group.fitness') }}
@endsection

@section('content')

    <form enctype="multipart/form-data" action="{{ action('Admin\PlanGroupController@fitness', $planGroup->uid) }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}
        <div class="">
        <div class="mc_section">
            <h2>{{ trans('messages.plan_group.fitness.title') }}</h2>
            <p>{{ trans('messages.plan_group.fitness.desc') }}</p>
        
            <ul class="mc-progress-list mt-5">
                @foreach ($planGroup->plansSendingServers as $planGroupsendingServer)
                    <li>
                        <div class="row">
                            <div class="col-md-3 text-end pt-10">
                                <label class="">
                                    {{ $planGroupsendingServer->sendingServer->name }}                                        
                                </label>
                            </div>
                            <div class="col-md-7">
                                <div class="d-flex">
                                    <div class="pull-left text-small me-auto">
                                        {{ trans('messages.plan_group.fitness.less') }}
                                    </div>
                                    <div class="pull-right text-small">
                                        {{ trans('messages.plan_group.fitness.more') }}
                                    </div>
                                </div>
                                    
                                <div>
                                    <input name="sending_servers[{{ $planGroupsendingServer->sendingServer->uid }}]" class="slider"
                                        data-slider-value="{{ $planGroupsendingServer->fitness }}"
                                        data-slider-min="1"
                                        data-slider-max="100"
                                        data-slider-step="1"
                                        data-slider-tooltip="hide"
                                    />
                                </div>
                                
                            </div>
                            <div class="col-md-2 pt-10">
                                <span class="mc-text-bold val hide">{{ $planGroupsendingServer->fitness }}</span>
                                <span class="mc-text-bold percent">{{ $planGroupsendingServer->fitness }}</span> (%)
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        </div>
        <hr>
        <div class="mt-4">
        <button type="submit" class="btn btn-secondary">{{ trans('messages.plan_group.fitness.save') }}</button>
        <button role="button" class="btn btn-link" data-dismiss="modal">{{ trans('messages.close') }}</button>
        </div>
    <form>


    <script>
        function fitnessCalc() {
            var sum = 0;
            $("input.slider").each(function() {
                var number = Math.round(parseFloat($(this).closest('li').find('.val').html()));
                sum = sum + number;
            });
            $("input.slider").each(function() {
                var number = Math.round(parseFloat($(this).closest('li').find('.val').html()));
                var percent = Math.round((number/sum)*100);
                
                $(this).closest('li').find('.percent').html(percent);
            });
        }

        $(function() {
            // slider
            $("input.slider").slider();
            $("input.slider").on("slide", function(slideEvt) {
                $(this).closest('li').find('.val').html(slideEvt.value);
                fitnessCalc();
            });
            fitnessCalc();
        });
    </script>
@endsection