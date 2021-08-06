<!doctype html>
<html>
    <head>
        <title>Form Builder</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        @include('layouts.core._favicon')
        
        <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"> 

        <style>
            .logo-img {
                width: 120px!important;
                margin-right: 15px;
            }
            #formCanvas {
                width: 70%;
            }
            #formWidgetPanel {
                width: 30%;
            }
            #builder_iframe {
                padding: 0;
            }
            .content-left {
                height: calc(100% - 55px);
            }
            .content-right .tab-pane .container {
                height: calc(100vh - 125px);
            }
            .acelle-top-menu {
                position: fixed;
                z-index: 1;
            }
        </style>
        
        <script>
            var CSRF_TOKEN = "{{ csrf_token() }}";
            var editor;
            var formFields = {!! json_encode($formFields) !!};
            
            $( document ).ready(function() {
                editor = new Editor({
                    strict: true,
                    showHelp: false,
                    showInlineToolbar: false,

                    formFields: formFields,
                    
                    root: '{{ URL::asset('builder') }}/',                
                    lang: {!! json_encode(language()->getBuilderLang()) !!},
                    url: 'https://acelle.com/templates/610c9fe8d36c9/builder/edit/content',
                    saveUrl: '',
                    saveMethod: 'POST',
                    uploadAssetUrl: '',
                    uploadAssetMethod: 'POST',
                    backCallback: function() {
                        
                    },

                    renderCanvas: function() {
                        return $('#formCanvas');
                    },
                    renderWidgetPanel: function() {
                        return $('#formWidgetPanel');
                    },

                    tags: [],

                    filemanager: '{{ URL::asset('filemanager2/dialog.php') }}',
                    logo: '{{ \Acelle\Model\Setting::get('site_logo_small') ? action('SettingController@file', \Acelle\Model\Setting::get('site_logo_small')) : URL::asset('images/logo_light_blue.svg') }}',
                    backgrounds: [
                        '{{ url('/images/backgrounds/images1.jpg') }}',
                        '{{ url('/images/backgrounds/images2.jpg') }}',
                        '{{ url('/images/backgrounds/images3.jpg') }}',
                        '{{ url('/images/backgrounds/images4.png') }}',
                        '{{ url('/images/backgrounds/images5.jpg') }}',
                        '{{ url('/images/backgrounds/images6.jpg') }}',
                        '{{ url('/images/backgrounds/images9.jpg') }}',
                        '{{ url('/images/backgrounds/images11.jpg') }}',
                        '{{ url('/images/backgrounds/images12.jpg') }}',
                        '{{ url('/images/backgrounds/images13.jpg') }}',
                        '{{ url('/images/backgrounds/images14.jpg') }}',
                        '{{ url('/images/backgrounds/images15.jpg') }}',
                        '{{ url('/images/backgrounds/images16.jpg') }}',
                        '{{ url('/images/backgrounds/images17.png') }}'
                    ]
                });
            
                editor.init();
            });
        </script>
    </head>
    <body>
        <div class="acelle-top-menu">
            <nav class="navbar fixed-top navbar-light bg-light shadow-sm">
                <a class="navbar-brand d-flex align-items-center pl-0" href="{{ action('Admin\HomeController@index') }}">
                    <span class="material-icons-outlined mr-2">
                        dashboard
                    </span>
                    Popup-Form Builder
                </a>
                <a class="navbar-brand d-flex align-items-center mr-0" href="#"><span class="material-icons-outlined">
                    close
                    </span></a>
            </nav>
        </div>
        <div class="d-flex">
            <div id="formCanvas"></div>
            <div id="formWidgetPanel"></div>
        </div>

        <style>
            .lds-dual-ring {
                display: inline-block;
                width: 80px;
                height: 80px;
            }
            .lds-dual-ring:after {
                content: " ";
                display: block;
                width: 30px;
                height: 30px;
                margin: 4px;
                border-radius: 80%;
                border: 2px solid #aaa;
                border-color: #007bff transparent #007bff transparent;
                animation: lds-dual-ring 1.2s linear infinite;
            }
            @keyframes lds-dual-ring {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
        <div style="text-align: center;
            height: 100vh;
            vertical-align: middle;
            padding: auto;
            display: flex;">
            <div style="margin:auto" class="lds-dual-ring"></div>
        </div>

        <script>
            $(function() {
                $('.lds-dual-ring').remove();
            })
        </script>
  </body>
</html>
