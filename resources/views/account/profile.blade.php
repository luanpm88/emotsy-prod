@extends('layouts.frontend')

@section('title', trans('messages.my_profile'))
	
@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/validation/validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
	<div class="page-header">
		<div class="page-header-content">			
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-profile"></i> {{ trans('messages.my_profile') }}</span>
				</h1>
			</div>
		</div>
	</div>
@endsection

@section('content')    
				<form enctype="multipart/form-data" action="{{ action('AccountController@profile') }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}
					
					<div class="row">
						<div class="col-md-3">
							<div class="sub_section">
								<h2 class="text-semibold text-teal-800">{{ trans('messages.profile_photo') }}</h2>
								<div class="media profile-image">
									<div class="media-left">
										<a href="#"><img src="{{ URL::asset('assets/images/placeholder.jpg') }}" class="img-circle" alt=""></a>
									</div>
									<div class="media-body text-center">
										<h5 class="media-heading text-semibold">{{ trans('messages.upload_your_photo') }}</h5>
										{{ trans('messages.photo_at_least', ["size" => "300px x 300px"]) }}
										<br /><br />
										<a href="" class="btn btn-xs bg-teal mr-10"><i class="icon-upload4"></i> {{ trans('messages.upload') }}</a>
										<a href="" class="btn btn-xs bg-grey-800"><i class="icon-trash"></i> {{ trans('messages.remove') }}</a>
									</div>
								</div>
							</div>								
						</div>
						<div class="col-md-5">
							<div class="sub_section">
								<h2 class="text-semibold text-teal-800">{{ trans('messages.basic_information') }}</h2>
									
								<div class="row">
									<div class="col-md-6">
										@include('helpers.form_control', ['type' => 'text', 'name' => 'first_name', 'value' => $user->first_name, 'rules' => Mailixa\Model\User::$rules])
									</div>
									<div class="col-md-6">
										@include('helpers.form_control', ['type' => 'text', 'name' => 'last_name', 'value' => $user->last_name, 'rules' => Mailixa\Model\User::$rules])
									</div>
								</div>
								
								@include('helpers.form_control', ['type' => 'select', 'name' => 'timezone', 'value' => $user->timezone, 'options' => Mailixa\Library\Tool::getTimezoneSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => Mailixa\Model\User::$rules])								
								
								@include('helpers.form_control', ['type' => 'select', 'name' => 'language_id', 'label' => trans('messages.language'), 'value' => $user->language_id, 'options' => Mailixa\Model\Language::getSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => Mailixa\Model\User::$rules])
								
							</div>
						</div>
						<div class="col-md-4">
							<div class="sub_section">
								<h2 class="text-semibold text-teal-800">{{ trans('messages.account') }}</h2>

								@include('helpers.form_control', ['type' => 'text', 'name' => 'email', 'value' => $user->email, 'help_class' => 'profile', 'rules' => Mailixa\Model\User::$rules])
								
								@include('helpers.form_control', ['type' => 'password', 'label'=> trans('messages.new_password'), 'name' => 'password', 'rules' => Mailixa\Model\User::$rules])
								
								@include('helpers.form_control', ['type' => 'password', 'name' => 'password_confirmation', 'rules' => Mailixa\Model\User::$rules])
								
							</div>
						</div>						
					</div>
					
					<div class="text-right">
						<button class="btn bg-teal mr-10"><i class="icon-check"></i> Save</button>
						<a href="/lists" class="btn bg-grey-800"><i class="icon-cross2"></i> Cancel</a>
					</div>
					
				<form>
	
@endsection