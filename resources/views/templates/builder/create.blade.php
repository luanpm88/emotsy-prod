@extends('layouts.frontend')

@section('title', trans('messages.create_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">				
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li><a href="{{ action("TemplateController@index") }}">{{ trans('messages.templates') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold">{{ trans('messages.template.new_template') }}</span>
        </h1>				
    </div>

@endsection

@section('content')
    
    <div class="row">
        <div class="col-md-6">
            <form action="{{ action('TemplateController@builderCreate') }}" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}
                
                <input type="hidden" value="" name="style" />
                
                <div class="sub_section">
                    @include('helpers.form_control', [
                        'type' => 'text',
                        'class' => '',
                        'name' => 'name',
                        'value' => $template->name,
                        'label' => 'Enter your template\'s name here',
                        'help_class' => 'template',
                        'rules' => ['name' => 'required']
                    ])
                </div>
            </form>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-6">
            <form action="{{ action('TemplateController@store') }}" method="POST" class="ajax_upload_form form-validate-jquery">
                {{ csrf_field() }}
                
                <div class="sub_section">
                    <h3 class="text-semibold">{{ trans('messages.template.select_your_template') }}</h3>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        @foreach(Acelle\Model\Template::templateStyles() as $name => $style)
            <div class="col-xxs-12 col-xs-6 col-sm-3 col-md-2">
                <a href="javascript:;" class="select-template-layout" data-value="{{ $name }}">
                    <div class="panel panel-flat panel-template-style">
                        <div class="panel-body">
                            <img src="{{ url('images/template_styles/'.$name.'.png') }}" />
                            <h5 class="mb-0 text-center">{{ trans('messages.'.$name) }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    
    <hr>
    <div class="text-left">
        <button class="btn bg-teal mr-10 start-design"><i class="icon-check"></i> {{ trans('messages.template.create_and_design') }}</button>
    </div>
        
    <script>
        $(document).ready(function() {
            $(document).on('click', '.select-template-layout', function() {
                var style = $(this).attr('data-value');
                
                // unselect all layouts
                $('.select-template-layout').removeClass('selected');
                
                // select this
                $(this).addClass('selected');
                
                // update style value
                $('[name=style]').val(style);
            });
            
            $('.select-template-layout').eq(0).click();
            
            $(document).on('click', '.start-design', function() {
                var form = $('form');
                
                if (form.valid()) {
                    form.submit();
                }
            });
        });
    </script>
    
@endsection
