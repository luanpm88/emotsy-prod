@extends('layouts.core.frontend')

@section('title', $list->name)

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('core/prismjs/prism.css') }}">
    <script type="text/javascript" src="{{ URL::asset('core/prismjs/prism.js') }}"></script>
@endsection

@section('page_header')

    @include("lists._header")

@endsection

@section('content')

    @include("lists._menu")

    <h2 class="text-semibold text-primary my-4">{{ trans('messages.Embedded_form') }}</h2>
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-semibold">{{ trans('messages.options') }}</h4>
            <form action="{{ action("MailListController@embeddedForm", $list->uid) }}" class="embedded-options-form">
                <div class="d-flex justify-content-space-between" style="width:100%;justify-content: space-between">
                    <div class="me-4">
                        @include('helpers.form_control', ['type' => 'text',
                                'name' => 'form_title',
                                'label' => trans('messages.form_title'),
                                'value' => trans('messages.Subscribe_to_our_mailing_list'),
                                'help_class' => 'list'
                        ])

                        @include('helpers.form_control', ['type' => 'text',
                                'name' => 'redirect_url',
                                'label' => trans('messages.list.embedded_form.redirect_url'),
                                'value' => '',
                                'help_class' => 'list',
                                'placeholder' => trans('messages.list.redirect_url.placeholder'),
                        ])
                    </div>
                    <div class="me-4">
                        <div class="form-group">
                            <label>{!! trans('messages.show_only_required_fields', ["link" => action('FieldController@index', $list->uid)]) !!}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'required_fields',
                                    'label' => '',
                                    'value' => 'no',
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ trans('messages.stylesheet_included') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'stylesheet',
                                    'label' => '',
                                    'value' => 'yes',
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="me-4">
                        <div class="form-group">
                            <label>{{ trans('messages.include_javascript') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'javascript',
                                    'label' => '',
                                    'value' => 'yes',
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('messages.embeded_form.show_invisible') }}</label>
                            <div class="notoping">
                                @include('helpers.form_control', ['type' => 'checkbox',
                                    'name' => 'show_invisible',
                                    'label' => '',
                                    'value' => 'no',
                                    'options' => ['no','yes'],
                                    'help_class' => 'list'
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="" style="width: 440px">

                                @include('helpers.form_control', ['type' => 'textarea',
                                    'name' => 'custom_css',
                                    'class' => 'height-100 text-small',
                                    'label' => trans('messages.custom_css'),
                                    'value' => ".subscribe-embedded-form {\n     color: #333\n}\n.subscribe-embedded-form label {\n     color: #555\n}",
                                    'help_class' => 'list'
                                ])

                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr />
    <div class="embedded-form-result">
        @if (array_key_exists('stylesheet', request()->all()))
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex">
                        <h4 class="text-semibold me-auto">{{ trans('messages.Copy_paste_onto_your_site') }}</h4>
                        <div>
                            <a href="javascript:;" onclick="copyToClipboard(htmlDecode($('.main-code').html()));" class="btn btn-sm btn-light">
                                <span class="material-icons-outlined">
                                    content_copy
                                    </span> {{ trans('messages.copy') }}</a>
                        </div>
                    </div>
                        
                        <pre class="language-markup content-group embedded-code"><code></code></pre>
                        <code style="height: 400px" class="form-control main-code hide">@include("lists._embedded_form_content", request()->all())</code>
                </div>
                <div class="col-md-6">
                    <h4 class="text-semibold">{{ trans('messages.preview') }}</h4>
                    <?php
                        $params = request()->all();
                        $params["uid"] = $list->uid;
                    ?>
                    <iframe class="embedded_form" src="{{ action("MailListController@embeddedFormFrame", $params) }}"></iframe>
                </div>
            </div>
        @endif
    </div>

    

    <script>
        function updateEmbeddedForm(form, url) {
            var data = {};
            form.serializeArray().forEach(function(entry) {
                if(entry.value!=="") {
                    data[entry.name] = entry.value;
                }
            });

            $.ajax({
                method: "GET",
                url: url,
                data: data
            })
            .done(function( msg ) {
                // $(".embedded-form-result").html($("<div>").html(msg).find(".embedded-form-result"));
                var html = $("<div>").html(msg).find(".embedded-form-result").html();
                $(".embedded-form-result").html(html);
                
                var bio_text = $(".main-code").html();
                bio_text = bio_text.replace(/\</g, '&lt;');
                bio_text = bio_text.replace(/script_tmp/g, 'script');
                bio_text = bio_text.replace(/\t/g, '');
                bio_text = bio_text.replace(/\n/g, '');
                bio_text = bio_text.replace(/\s+/g, ' ');
                bio_text = bio_text.replace(/\>\s*&lt;/g, "&gt;\n&lt;");
                bio_text = bio_text.replace(/\s+\{\s+/g, "{");
                $("code").html(bio_text);
                
                // Hightlight code
                Prism.highlightAll();
            });
        }

        $(function() {
            //embedded-options-form
            $(document).on("change", ".embedded-options-form input[type=text], .embedded-options-form textarea, .embedded-options-form input[name='required_fields'], .embedded-options-form input[name='show_invisible'], .embedded-options-form input[name='javascript'], .embedded-options-form input[name='stylesheet']", function() {
                var url = $(this).parents("form").attr("action");

                updateEmbeddedForm($(this).parents("form"), url);
            });

            //embedded-options-form
            $(document).on("keyup", ".embedded-options-form input[type=text]", function() {
                var url = $(this).parents("form").attr("action");

                updateEmbeddedForm($(this).parents("form"), url);
            });

            $(".embedded-options-form input").trigger("change");

        });
    </script>
@endsection
