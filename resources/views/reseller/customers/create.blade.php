@extends('layouts.reseller')

@section('title', trans('messages.create_customer'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("Reseller\CustomerController@index") }}">{{ trans('messages.customers') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-plus-circle2"></i> {{ trans('messages.create_customer') }}</span>
				</h1>
			</div>

@endsection

@section('content')
          <form enctype="multipart/form-data" action="{{ action('Reseller\CustomerController@store') }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					
					@include('reseller.customers._form')			
					
				<form>
				
@endsection
