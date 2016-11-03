@extends('layouts.backend')

@section('title', $language->name)
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/ace/ace/ace.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/ace/ace/theme-twilight.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/ace/ace/mode-php.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/ace/jquery-ace.js') }}"></script>
		
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
					
                    <div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-top">
							<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
								<i class="icon-menu6"></i> {{ trans('messages.messages') }}</a>
							</li>
						</ul>
							
						<div class="tab-content">
							<div class="tab-pane active" id="top-tab1">
								<textarea name="messages" class="my-code-messages" rows="20" style="width: 100%">{!! $messages !!}</textarea>
							</div>							
						</div>
					</div>
					<br />
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-top">
							<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
								<i class="icon-menu6"></i> {{ trans('messages.auth') }}</a>
							</li>
						</ul>
							
						<div class="tab-content">
							<div class="tab-pane active" id="top-tab1">
								<textarea name="auth" class="my-code-messages" rows="20" style="width: 100%">{!! $auth !!}</textarea>								
							</div>							
						</div>
					</div>
					
					<br />
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-top">
							<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
								<i class="icon-menu6"></i> {{ trans('messages.validation') }}</a>
							</li>
						</ul>
							
						<div class="tab-content">
							<div class="tab-pane active" id="top-tab1">
								<textarea name="validation" class="my-code-messages" rows="20" style="width: 100%">{!! $validation !!}</textarea>								
							</div>							
						</div>
					</div>
						
					<br />
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-top">
							<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
								<i class="icon-menu6"></i> {{ trans('messages.passwords') }}</a>
							</li>
						</ul>
							
						<div class="tab-content">
							<div class="tab-pane active" id="top-tab1">
								<textarea name="passwords" class="my-code-messages" rows="20" style="width: 100%">{!! $passwords !!}</textarea>								
							</div>							
						</div>
					</div>
						
					<br />
					<div class="tabbable">
						<ul class="nav nav-tabs nav-tabs-top">
							<li class="active text-semibold"><a href="#top-tab1" data-toggle="tab">
								<i class="icon-menu6"></i> {{ trans('messages.pagination') }}</a>
							</li>
						</ul>
							
						<div class="tab-content">
							<div class="tab-pane active" id="top-tab1">
								<textarea name="pagination" class="my-code-messages" rows="20" style="width: 100%">{!! $pagination !!}</textarea>								
							</div>							
						</div>
					</div>
						
					<hr>
					<div class="text-right">
						<button class="btn bg-teal"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
					</div>
					
				<form>
				
				<script>
					$('.my-code-messages').ace({ theme: 'twilight', lang: 'php' });
				</script>
	
@endsection