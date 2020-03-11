@extends('layouts.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.template'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>        
    <script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>	
@endsection

@section('page_header')
	
    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-paperplane"></i> {{ $campaign->name }}</span>
        </h1>

        @include('campaigns._steps', ['current' => 3])
    </div>

@endsection

@section('content')
    <form action="{{ action('CampaignController@builderClassic', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
        {{ csrf_field() }}

        <div class="d-flex align-items-canter">
            <h2 class="mt-0 mr-auto">{{ trans('messages.email_content') }}</h2>
            <a href="{{ action('CampaignController@builderPlainEdit', [
                'uid' => $campaign->uid
            ]) }}" class="btn btn-default">
                {{ trans('messages.campaign.plain_text_editor') }}
            </a>
        </div>
        
        <div class="tab-content">
            
            <div class="tab-pane active" id="top-justified-divided-tab1">
                @include('helpers.form_control', ['type' => 'textarea',
                    'class' => 'template-editor',
                    'name' => 'html',
                    'label' => '',
                    'value' => $campaign->render(),
                    'rules' => [],
                    'help_class' => 'campaign'
                ])
            </div>
        </div>
            
        @include('elements._tags', ['tags' => Acelle\Model\Template::tags($campaign->defaultMailList)])
        
        <hr>
        <div class="text-right">
            <button class="btn bg-teal-800">{{ trans('messages.save') }}</button>
        </div>
        
    <form>		

    <script>
        var editor;
        $(document).ready(function() {
            editor = tinymce.init({
                selector: '.template-editor',
                height: 500,
                convert_urls: false,
                remove_script_host: false,
                forced_root_block: "",
                plugins: [
                'fullpage table advlist autolink lists link image charmap print preview anchor directionality',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media contextmenu paste code textcolor'
                ],
                toolbar: 'tags insertfile undo redo | fontselect | fontsizeselect | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | ltr rtl',
                valid_elements : '*[*],meta[*]',
                extended_valid_elements : "meta[*]",
                valid_children : "+body[style],+body[meta],+div[h2|span|meta|object],+object[param|embed]",
                content_css: [
                    APP_URL.replace('/index.php','')+'/tinymce/skins/lightgray/content.fixed.css',
                ],
                external_filemanager_path:APP_URL.replace('/index.php','')+"/filemanager/",
                filemanager_title:"Responsive Filemanager" ,
                external_plugins: { "filemanager" : APP_URL.replace('/index.php','')+"/filemanager/plugin.min.js"},
                setup: function(editor) {
                    editor.addButton('tags', {
                        type: 'listbox',
                        text: '{{ trans('messages.editor.insert_tag') }}',
                        icon: false,
                        onselect: function(e) {                        
                            editor.insertContent(this.value());
                            $('#mceu_0 .mce-txt').html('{{ trans('messages.editor.insert_tag') }}');
                        },
                        values: [
                            @foreach(Acelle\Model\Template::tags() as $tag)
                                {text: '{{ "{".$tag["name"]."}" }}', value: '{{ "{".$tag["name"]."}" }}'},
                            @endforeach
                        ],
                        onPostRender: function() {
                            // Select the firts item by default
                            this.value('JustifyLeft');
                        }
                    });
                }
            });
        });
    </script>
@endsection
