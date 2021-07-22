@extends('layouts.core.frontend')

@section('title', $list->name . ": " . trans('messages.create_subscriber'))
	
@section('page_header')
			
			@include("lists._header")

@endsection

@section('content')
	
				@include("lists._menu")
				
				<h2 class="text-semibold text-primary"><span class="material-icons-outlined">
add
</span> {{ trans('messages.create_segment') }}</h2>
				
                <form action="{{ action('SegmentController@store', $list->uid) }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					
					<div class="row">
						<div class="col-md-12">
							@include("segments._form")
							<hr>
							<div class="text-left">
								<button class="btn btn-secondary me-2"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
								<a href="{{ action('SegmentController@index', $list->uid) }}" class="btn btn-link"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
							</div>
						</div>
					</div>
				<form>
@endsection
