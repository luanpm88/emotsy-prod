@extends('layouts.popup.medium')

@section('title')
    {{ trans('messages.plan_group.sending_server.option') }}
@endsection

@section('content')
    <form enctype="multipart/form-data" action="{{ action('Admin\PlanGroupController@sendingServerOption', ['uid' => $planGroup->uid]) }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}

            <h2 class="mt-0">{{ trans('messages.plan_group.sending_server') }}</h2>
                            
            <p>{{ trans('messages.plan_group.sending_server.intro') }}</p>
            
            <div class="form-group control-radio">
                <div class="radio_box" data-popup='tooltip' title="">
                    <label class="main-control">
                        <div class="">
                            <input {{ ($planGroup->getOption('sending_server_option') == \Acelle\Model\PlanGroup::SENDING_SERVER_OPTION_SYSTEM ? 'checked' : '') }} type="radio"
                                name="plan[options][sending_server_option]"
                                value="{{ \Acelle\Model\PlanGroup::SENDING_SERVER_OPTION_SYSTEM }}" class="styled" />
                            
                            <rtitle>{{ trans('messages.plan_group.option.system_s_sending_server') }}</rtitle>
                        </div>
                            
                        <div class="desc text-normal mb-10">
                            {{ trans('messages.plan_group.option.system_s_sending_server.intro') }}
                        </div>
                    </label>
                </div>
                <hr>
                <div class="radio_box" data-popup='tooltip' title="">
                    <label class="main-control">
                        <div class="">
                            <input {{ ($planGroup->getOption('sending_server_option') == \Acelle\Model\PlanGroup::SENDING_SERVER_OPTION_OWN ? 'checked' : '') }} type="radio"
                                name="plan[options][sending_server_option]"
                                value="{{ \Acelle\Model\PlanGroup::SENDING_SERVER_OPTION_OWN }}" class="styled" />
                                <rtitle>{{ trans('messages.plan_group.option.own_sending_server') }}</rtitle>
                        </div>
                        <div class="desc text-normal mb-10">
                            {{ trans('messages.plan_group.option.own_sending_server.intro') }}
                        </div>
                    </label>
                </div>
                <hr>
                <div class="radio_box pe-none" style="opacity: 0.6;" data-popup='tooltip' title="">
                    <label class="main-control">
                        <div class="">
                            <input {{ ($planGroup->getOption('sending_server_option') == \Acelle\Model\PlanGroup::SENDING_SERVER_OPTION_SUBACCOUNT ? 'checked' : '') }} type="radio"
                            name="plan[options][sending_server_option]"
                            value="{{ \Acelle\Model\PlanGroup::SENDING_SERVER_OPTION_SUBACCOUNT }}" class="styled" />
                            <rtitle>{{ trans('messages.plan_group.option.sub_account') }}</rtitle>
                        </div>
                        <div class="desc text-normal mb-10">
                            {{ trans('messages.plan_group.option.sub_account.intro') }}
                        </div>
                    </label>
                </div>
            <button class="btn btn-secondary me-2">{{ trans('messages.save') }}</button>
        </form>
@endsection