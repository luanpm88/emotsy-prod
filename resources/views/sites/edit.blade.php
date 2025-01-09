@extends('layouts.core.frontend', [
	'menu' => 'site',
])

@section('title', trans('messages.site.add_new'))

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("SiteController@index") }}">{{ trans('messages.site.landing_pages') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><span class="material-symbols-rounded">edit</span>
			<span class="text-semibold">{{ $site->name }}</span>
		</h1>
	</div>

@endsection

@section('content')

	@include('sites._menu', [
		'menu' => 'general',
	])

	<div class="row">
		<div class="col-sm-12 col-md-10 col-lg-10">
			<p>{!! trans('messages.site.create.intro') !!}</p>
		</div>
	</div>

	<form action="{{ action('SiteController@update', $site->uid) }}" method="POST" class="">
		@csrf

		@include('sites._form')

		<hr >
		<div class="text-end">
			<a href="{{ action('SiteController@index') }}" class="btn btn-link me-1"><i class="icon-check"></i> {{ trans('messages.cancel') }}</a>
			<button class="btn btn-primary me-1"><i class="icon-check"></i> {{ trans('messages.site.save_and_continue') }}</button>
		</div>
	</form>
    
@endsection
