@extends('layouts.subscription')

@section('title', trans('messages.select_a_plan'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
    
    <script type="text/javascript" src="{{ URL::asset('js/chart.js') }}"></script>
@endsection

@section('content')
    @include('subscriptions._steps')
    
    <form enctype="multipart/form-data" action="{{ action('SubscriptionController@register') }}" method="POST" class="form-validate-jqueryz subscription-form">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                @include('common.errors')
                
                <h2 class="mt-0"><i class="icon-quill4"></i> {{ trans('messages.customer_account') }}</h2>
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="sub_section">
                                    <h2 class="text-semibold text-teal-800">{{ trans('messages.profile_photo') }}</h2>
                                    <div class="media profile-image">
                                        <div class="media-left">
                                            <a href="#" class="upload-media-container">
                                                <img preview-for="image" empty-src="{{ URL::asset('assets/images/placeholder.jpg') }}" src="{{ action('CustomerController@avatar', $customer->uid) }}" class="img-circle" alt="">
                                            </a>
                                            <input type="file" name="image" class="file-styled previewable hide">
                                            <input type="hidden" name="_remove_image" value='' />
                                        </div>
                                        <div class="media-body text-center">
                                            <h5 class="media-heading text-semibold">{{ trans('messages.upload_photo') }}</h5>
                                            {{ trans('messages.photo_at_least', ["size" => "300px x 300px"]) }}
                                            <br /><br />
                                            <a href="#upload" onclick="$('input[name=image]').trigger('click')" class="btn btn-xs bg-teal mr-10"><i class="icon-upload4"></i> {{ trans('messages.upload') }}</a>
                                            <a href="#remove" class="btn btn-xs bg-grey-800 remove-profile-image"><i class="icon-trash"></i> {{ trans('messages.remove') }}</a>
                                        </div>
                                    </div>
                                </div>								
                            </div>
                            <div class="col-md-9">
                                <div class="sub_section">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h2 class="text-semibold text-teal-800">{{ trans('messages.account') }}</h2>
                                        </div>
                                        <div class="col-md-7">
                                            <h2 class="text-semibold text-teal-800">{{ trans('messages.basic_information') }}</h2>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-5">
                                            @include('helpers.form_control', ['type' => 'text', 'name' => 'email', 'value' => $customer->email(), 'help_class' => 'profile', 'rules' => $customer->rules()])
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @include('helpers.form_control', ['type' => 'text', 'name' => 'first_name', 'value' => $customer->first_name, 'rules' => $customer->rules()])
                                                </div>
                                                <div class="col-md-6">
                                                    @include('helpers.form_control', ['type' => 'text', 'name' => 'last_name', 'value' => $customer->last_name, 'rules' => $customer->rules()])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-5">
                                            @include('helpers.form_control', ['type' => 'password', 'label'=> trans('messages.new_password'), 'name' => 'password', 'rules' => $customer->rules()])
                                        </div>
                                        <div class="col-md-7">
                                            @include('helpers.form_control', ['type' => 'select', 'name' => 'timezone', 'value' => $customer->timezone, 'options' => Tool::getTimezoneSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => $customer->rules()])								
                                        </div>
                                    </div>
                                        
                                    <div class="row">
                                        <div class="col-md-5">
                                            @include('helpers.form_control', ['type' => 'password', 'name' => 'password_confirmation', 'rules' => $customer->rules()])
                                        </div>
                                        <div class="col-md-7">
                                            @include('helpers.form_control', ['type' => 'select', 'name' => 'language_id', 'label' => trans('messages.language'), 'value' => $customer->language_id, 'options' => Acelle\Model\Language::getSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => $customer->rules()])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <h2 class="mt-30"><i class="icon-user"></i> {{ trans('messages.plan_information') }}</h2>
                <div class="panel">
                    <div class="panel-body">
                        @include('subscriptions._form')                
                    </div>
                </div>
                
                <br />
                <div class="text-center">
                    <button type='submit' class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.register') }}</button>
                </div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </form>
    
@endsection
