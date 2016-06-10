@extends('layouts.frontend')

@section('title', trans('messages.my_profile'))

@section('page_header')
	<div class="page-header">
		<div class="page-header-content">			
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="/lists">Lists</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-plus-circle2"></i> {{ trans('messages.home') }}Updt</span>
				</h1>
			</div>
		</div>
	</div>
@endsection

@section('content')
    
@endsection