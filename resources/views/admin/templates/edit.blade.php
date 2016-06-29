@extends('layouts.backend')

@section('title', trans('messages.edit_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection

@section('page_header')

			<div class="page-title">				
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                    <li><a href="{{ action("TemplateController@index") }}">{{ trans('messages.templates') }}</a></li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-pencil"></i> {{ trans('messages.edit_template') }}</span>
				</h1>				
			</div>

@endsection

@section('content')
    
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ action('Admin\TemplateController@update', $template->uid) }}" method="POST" class="ajax_upload_form form-validate-jquery">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PATCH">
                            
							@include('helpers.form_control', ['required' => true, 'type' => 'text', 'label' => trans('messages.template_name'), 'name' => 'name', 'value' => $template->name, 'rules' => ['name' => 'required']])
							
							@include('helpers.form_control', ['class' => 'clean-editor','required' => true, 'type' => 'textarea', 'name' => 'content', 'value' => $template->content, 'rules' => ['content' => 'required']])
							
							<div class="text-right">
                                <button class="btn bg-teal mr-10"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
                                <a href="{{ action('Admin\TemplateController@index') }}" class="btn bg-grey-800"><i class="icon-cross2"></i> {{ trans('messages.cancel') }}</a>
                            </div>
                            
                        </form>  
                        
                    </div>
                </div>
@endsection
