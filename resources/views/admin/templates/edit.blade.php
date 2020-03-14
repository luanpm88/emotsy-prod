@extends('layouts.black')

@section('title', trans('messages.edit_template'))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('js/tinymce/tinymce.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
@endsection

@section('content')
    <header>
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="height: 56px;">
			<a class="navbar-brand left-logo mr-0" href="#">
				@if (\Acelle\Model\Setting::get('site_logo_small'))
					<img src="{{ action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) }}" alt="">
				@else
					<img height="22" src="{{ URL::asset('images/logo_light.png') }}" alt="">
				@endif
			</a>
			<div class="d-inline-block d-flex mr-auto align-items-center">
                <a style="" href="{{ action('Admin\TemplateController@index') }}" class="action black-back-button mr-3">
					<i class="material-icons-outlined">arrow_back</i>
				</a>
                <h1 class="">{{ $template->name }}</h1>
				<i class="material-icons-outlined automation-head-icon ml-2">web</i>
			</div>
			<div class="automation-top-menu">
				<button class="btn btn-primary" onclick="$('#classic-builder-form').submit()">{{ trans('messages.save') }}</button>
			</div>
		</nav>
	</header>
    <form id="classic-builder-form" style="margin-top: 56px;" action="{{ action('Admin\TemplateController@update', $template->uid) }}" method="POST" class="ajax_upload_form form-validate-jquery">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PATCH">

        <div class="row mr-0 ml-0">
            <div class="col-md-9 pl-0 pb-0 pr-0">
                @include('helpers.form_control', [
                    'class' => 'template-editor',
                    'label' => '',
                    'required' => true,
                    'type' => 'textarea',
                    'name' => 'content',
                    'value' => $template->render(),
                    'rules' => ['content' => 'required']
                ])                
            </div>
            <div class="col-md-3 pr-0 pb-0 sidebar pr-4 pt-4 pl-4" style="overflow:auto;background:#f5f5f5">
                @include('helpers.form_control', [
                    'required' => true,
                    'type' => 'text',
                    'label' => trans('messages.template_name'),
                    'name' => 'name',
                    'value' => $template->name,
                    'rules' => ['name' => 'required']
                ])
                <hr>
                @include('elements._tags', ['tags' => Acelle\Model\Template::tags()])
            </div>            
        </div>   
    </form> 

    <script>
        $('.sidebar').css('height', $(window).height()-56);

        var editor;
        $(document).ready(function() {
            editor = tinymce.init({
                selector: '.template-editor',
                height: $(window).height()-56,
                convert_urls: false,
                remove_script_host: false,
                skin: "oxide-dark",
                forced_root_block: "",
                plugins: [
                'fullpage table advlist autolink lists link image charmap print preview anchor directionality',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media contextmenu paste code textcolor'
                ],
                toolbar: 'acelletags insertfile undo redo | fontselect | fontsizeselect | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | ltr rtl',
                valid_elements : '*[*],meta[*]',
                extended_valid_elements : "meta[*]",
                valid_children : "+body[style],+body[meta],+div[h2|span|meta|object],+object[param|embed]",
                content_css: [
                    APP_URL.replace('/index.php','')+'/tinymce/skins/lightgray/content.fixed.css',
                ],
                external_filemanager_path:APP_URL.replace('/index.php','')+"/filemanager2/",
                filemanager_title:"Responsive Filemanager" ,
                external_plugins: { "filemanager" : APP_URL.replace('/index.php','')+"/filemanager2/plugin.min.js"},
                setup: function (editor) {
                    
                    /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                    /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                    editor.ui.registry.addMenuButton('acelletags', {
                        text: '{{ trans('messages.editor.insert_tag') }}',
                        fetch: function (callback) {
                        var items = [];

                        @foreach(Acelle\Model\Template::tags() as $tag)
                            items.push({
                                type: 'menuitem',
                                text: '{{ "{".$tag["name"]."}" }}',
                                onAction: function (_) {
                                    editor.insertContent('{{ "{".$tag["name"]."}" }}');
                                }
                            });
                        @endforeach

                        callback(items);
                        }
                    });
                }
            });
        });
    </script>
@endsection
