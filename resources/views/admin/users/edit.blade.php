@extends('layouts.frontend')

@section('title', trans('messages.my_profile'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li class="active">{{ trans('messages.profile') }}</li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-profile"></i> {{ $user->displayName() }}</span>
				</h1>
			</div>
				
@endsection

@section('content')
	
				<form enctype="multipart/form-data" action="{{ action('Admin\UserController@update', $user->uid) }}" method="POST" class="form-validate-jquery">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include('admin.users._form')
					
				<form>
	
@endsection