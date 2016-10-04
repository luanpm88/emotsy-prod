@extends('layouts.frontend')

@section('title', trans('messages.create_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection

@section('page_header')

			<div class="page-title">				
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                    <li><a href="{{ action("TemplateController@index") }}">{{ trans('messages.templates') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-pencil"></i> {{ trans('messages.select_a_template_style') }}</span>
				</h1>				
			</div>

@endsection

@section('content')
    
                <div class="row">
                    <div class="col-xs-6 col-sm-4 col-md-3">
                        <a href="{{ action('TemplateController@build', ['style' => '1_column']) }}">
                            <div class="panel panel-flat panel-template-style">
                                <div class="panel-body">
                                    <img src="{{ url('images/template_styles/n1.png') }}" />
                                    <h5 class="mb-0 text-center">{{ trans('messages.1_column') }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-3">
                        <a href="{{ action('TemplateController@build', ['style' => '1_2_column']) }}">
                            <div class="panel panel-flat panel-template-style">
                                <div class="panel-body">
                                    <img src="{{ url('images/template_styles/1m.png') }}" />
                                    <h5 class="mb-0 text-center">{{ trans('messages.1_2_column') }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
@endsection
