@extends('layouts.frontend')

@section('title', $list->name)
	
@section('page_script')    
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("MailListController@index") }}">{{ trans('messages.lists') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-pencil7"></i> {{ $list->name }}</span>
				</h1>
			</div>

@endsection

@section('content')
                
                @include("lists._menu")
    
                <form action="{{ action('MailListController@update', $list->uid) }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					<input type="hidden" name="_method" value="PATCH">
					
					@include("lists._form")					
					
					<div class="text-right">
						<button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
						<a href="/lists" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
					</div>
					
				<form>
					
				
@endsection
