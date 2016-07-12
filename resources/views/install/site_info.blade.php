@extends('layouts.install')

@section('title', trans('messages.site_info'))

@section('page_script')    
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('content')

<form action="{{ action('InstallController@siteInfo') }}" method="POST" class="form-validate-jqueryz">
	<h3 class="text-teal-800">{{ trans('messages.general') }}</h3>
    <div class="row">
        <div class="col-md-12">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'site_name',
                'value' => (isset($site_info["site_name"]) ? $site_info["site_name"] : ""),
                'help_class' => 'install',
                'rules' => ["site_name" => "required"]
            ])
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'site_keyword',
                'value' => (isset($site_info["site_keyword"]) ? $site_info["site_keyword"] : ""),
                'help_class' => 'install',
                'rules' => ["site_keyword" => "required"]
            ])
            @include('helpers.form_control', [
                'type' => 'textarea',
                'name' => 'site_description',
                'value' => (isset($site_info["site_description"]) ? $site_info["site_description"] : ""),
                'help_class' => 'install',
                'rules' => ["site_description" => "required"]
            ])
            
        </div>
    </div>
	<hr />
	<h3 class="text-teal-800">{{ trans('messages.admin_info') }}</h3>
	<div class="row">
        <div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'first_name',
                'value' => (isset($site_info["first_name"]) ? $site_info["first_name"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
			
			@include('helpers.form_control', [
                'type' => 'text',
                'name' => 'email',
                'value' => (isset($site_info["email"]) ? $site_info["email"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
			
			@include('helpers.form_control', ['type' => 'select', 'name' => 'timezone', 'value' => (isset($site_info["timezone"]) ? $site_info["timezone"] : ""), 'options' => Tool::getTimezoneSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => $rules])
            
        </div>
		<div class="col-md-6">
            @include('helpers.form_control', [
                'type' => 'text',
                'name' => 'last_name',
                'value' => (isset($site_info["last_name"]) ? $site_info["last_name"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])
			
			@include('helpers.form_control', [
                'type' => 'text',
                'name' => 'password',
                'value' => (isset($site_info["password"]) ? $site_info["password"] : ""),
                'help_class' => 'install',
                'rules' => $rules
            ])            
        </div>
    </div>
	
	<hr >
	<div class="text-right">                                    
		<button data-wait="{{ trans('messages.button_processing') }}" type="submit" class="btn btn-primary bg-teal">{!! trans('messages.next') !!} <i class="icon-arrow-right14 position-right"></i></button>
	</div>
	
</form>
@endsection
