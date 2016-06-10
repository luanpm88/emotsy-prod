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
						<div class="col-md-6">
							<div class="sub_section">
								<h2 class="text-semibold text-teal-800">{{ trans('messages.profile_photo') }}</h2>
								<div class="media profile-image">
									<div class="media-left">
										<a href="#"><img src="{{ URL::asset('assets/images/placeholder.jpg') }}" class="img-circle" alt=""></a>
									</div>
									<div class="media-body">
										<h5 class="media-heading text-semibold">{{ trans('messages.upload_your_photo') }}</h5>
										{{ trans('messages.photo_at_least', ["size" => "300px x 300px"]) }}
										<br /><br />
										<a href="" class="btn btn-xs bg-teal">{{ trans('messages.upload_photo') }}</a>
										<a href="" class="btn btn-xs bg-grey">{{ trans('messages.remove_photo') }}</a>
									</div>
								</div>
							</div>								
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="sub_section">
								<h2 class="text-semibold text-teal-800">{{ trans('messages.basic_information') }}</h2>

								@include('helpers.text_field', ['name' => 'email', 'value' => $user->email, 'help_class' => 'profile', 'rules' => \Mailixa\User::$rules])
								
								@include('helpers.text_field', ['name' => 'first_name', 'value' => $user->first_name, 'rules' => \Mailixa\User::$rules])
								
							</div>
						</div>
						<div class="col-md-6">
							<div class="sub_section">
								<h2 class="text-semibold text-teal-800">{{ trans('messages.change_password') }}</h2>							
							</div>
						</div>
					</div>
					
					<div class="text-right">
						<button class="btn bg-teal"><i class="icon-check"></i> Save</button>
						<a href="/lists" class="btn bg-grey-800"><i class="icon-cross2"></i> Cancel</a>
					</div>
					
				<form>
	
@endsection