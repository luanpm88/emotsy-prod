@extends('layouts.install')

@section('title', trans('messages.database'))

@section('content')

<h3 class="text-teal-800"><i class="icon-database"></i> {{ trans('messages.database_configuration') }}</h3>

    <h5 class="">
        The settings was successfully configured! Click <span class="text-semibold">{!! trans('messages.setup_database') !!}</span> button to start importing data to database '{{ $database["database_name"] }}'.
    </h5>

	@if ($tables_exist)
		<div class="alert alert-danger">
			Acelle Mail is going to initialize your database, all existing data will be erased
		</div>
	@endif

    <div class="text-end">
        <a href="{{ action('InstallController@import') }}" class="btn btn-secondary">{!! trans('messages.setup_database') !!} <i class="icon-arrow-right14 position-right"></i></a>
		<a href="{{ action('InstallController@database') }}" class="btn btn-info bg-grey-600"><i class="icon-undo2"></i> {!! trans('messages.back') !!}</a>
    </div>

@endsection
