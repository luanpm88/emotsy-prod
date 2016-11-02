@extends('layouts.backend')

@section('title', $language->name)
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-pencil"></i> {{ $language->name }}</span>
				</h1>
			</div>
				
@endsection

@section('content')
	
				<form enctype="multipart/form-data" action="{{ action('Admin\LanguageController@translate', $language->uid) }}" method="POST" class="form-validate-jqueryx">
					{{ csrf_field() }}
					
                    @foreach($sentences as $key => $value)
                        <div class="row">
                            <div class="col-md-3">
                                <label class="text-semibold">{{ $key }}</label>
                            </div>
                            <div class="col-xs-9 col-sm-10 col-md-7">
                                @include('helpers.form_control', [
                                    'type' => 'text',
                                    'label' => '',
                                    'class' => '',
                                    'name' => $key,
                                    'value' => $value,
                                    'help_class' => 'language'
                                ])
                            </div>
                            <div class="col-xs-3 col-sm-2 col-md-2">
                                <button class="btn btn-info bg-info-800">Save</button>
                            </div>
                        </div>
                    @endforeach
					
				<form>
	
@endsection