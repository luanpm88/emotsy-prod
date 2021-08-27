@extends('layouts.core.backend')

@section('title', trans('messages.my_profile'))

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('messages.profile') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-icons-round">
                            person_outline
                            </span> {{ $user->displayName() }}</span>
        </h1>
    </div>

@endsection

@section('content')

    @include("admin.account._menu")

    <form enctype="multipart/form-data" action="{{ action('Admin\AccountController@profile') }}" method="POST" class="form-validaate-jquery">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-3">
                <div class="sub_section">
                    <h3 class="text-semibold text-primary mb-4">{{ trans('messages.profile_photo') }}</h3>
                    <div class="media profile-image">
                        <div class="media-left">
                            <a href="#" class="upload-media-container">
                                <img preview-for="image" empty-src="{{ URL::asset('images/placeholder.jpg') }}" src="{{ $user->getProfileImageUrl() }}" class="rounded-circle" alt="">
                            </a>
                            <input type="file" name="image" class="file-styled previewable hide">
                            <input type="hidden" name="_remove_image" value='' />
                        </div>
                        <div class="media-body text-center">
                            <h5 class="media-heading text-semibold">{{ trans('messages.upload_your_photo') }}</h5>
                            {{ trans('messages.photo_at_least', ["size" => "300px x 300px"]) }}
                            <br /><br />
                            <a href="#upload" onclick="$('input[name=image]').trigger('click')" class="btn btn-primary me-1"><span class="material-icons-outlined">
file_download
</span> {{ trans('messages.upload') }}</a>
                            <a href="#remove" class="btn btn-secondary remove-profile-image"><span class="material-icons-outlined">
delete_outline
</span> {{ trans('messages.remove') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="sub_section">
                    <h3 class="text-semibold text-primary mb-4">{{ trans('messages.basic_information') }}</h3>

                    <div class="row">
                        <div class="col-md-6">
                            @include('helpers.form_control', ['type' => 'text', 'name' => 'first_name', 'value' => $user->first_name, 'rules' => $user->rules()])
                        </div>
                        <div class="col-md-6">
                            @include('helpers.form_control', ['type' => 'text', 'name' => 'last_name', 'value' => $user->last_name, 'rules' => $user->rules()])
                        </div>
                    </div>

                    @include('helpers.form_control', ['type' => 'select', 'name' => 'timezone', 'value' => $admin->timezone, 'options' => Tool::getTimezoneSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => $user->rules()])

                    @include('helpers.form_control', ['type' => 'select', 'name' => 'language_id', 'label' => trans('messages.language'), 'value' => $admin->language_id, 'options' => Acelle\Model\Language::getSelectOptions(), 'include_blank' => trans('messages.choose'), 'rules' => $user->rules()])

                    <h3 class="text-semibold text-primary mb-4 mt-5">{{ trans('messages.account.personality') }}</h3>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="mb-2">{{ trans('messages.account.menu_layout') }}</label>
                            <div>
                                <label class="layout-select me-3">
                                    <input type="radio" name="menu_layout" value="top" {{ $admin->menu_layout == 'top' || $admin->menu_layout == 'none' ? 'checked' : '' }} class="styled mr-2" />
                                    <img src="{{ url('images/layout-menu-top.svg') }}" width="150px" />
                                </label>
                                <label class="layout-select">
                                    <input type="radio" name="menu_layout" value="left" {{ $admin->menu_layout == 'left' ? 'checked' : '' }} class="styled mr-2"  />
                                    <img src="{{ url('images/layout-menu-left.svg') }}" width="150px" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="mb-3">{{ trans('messages.theme_mode') }}</label>
                            <div class="mb-4">
                                <label class="dark-mode-select me-3">
                                    <input style="display:none" type="radio" name="dark_mode" value="1" {{ $admin->dark_mode ? 'checked' : '' }} class="styled mr-2" />
                                    <div class="theme-mode-icon dark-mode shadow">
                                        <svg class="SVGInline-svg SVGInline--cleaned-svg SVG-svg DarkModeIcon-svg SVG--color-svg" style="width: 12px;height: 12px;" viewBox="0 0 17 16" xmlns="http://www.w3.org/2000/svg"><path d="M7.914 0a6.874 6.874 0 0 0-1.26 3.972c0 3.875 3.213 7.017 7.178 7.017.943 0 1.843-.178 2.668-.5C15.423 13.688 12.34 16 8.704 16 4.174 16 .5 12.41.5 7.982.5 3.814 3.754.389 7.914 0z" fill-rule="evenodd"></path></svg>
                                    </div>
                                </label>
                                <label class="dark-mode-select">
                                    <input style="display:none" type="radio" name="dark_mode" value="0" {{ !$admin->dark_mode ? 'checked' : '' }} class="styled mr-2"  />
                                    <div class="theme-mode-icon light-mode shadow-sm">
                                        <svg class="SVGInline-svg SVGInline--cleaned-svg SVG-svg LightModeIcon-svg SVG--color-svg" style="width: 12px;height: 12px;" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M3.828 5.243L2.343 3.757a1 1 0 0 1 1.414-1.414l1.486 1.485a5.027 5.027 0 0 0-1.415 1.415zM7 3.1V1a1 1 0 1 1 2 0v2.1a5.023 5.023 0 0 0-2 0zm3.757.728l1.486-1.485a1 1 0 1 1 1.414 1.414l-1.485 1.486a5.027 5.027 0 0 0-1.415-1.415zM12.9 7H15a1 1 0 0 1 0 2h-2.1a5.023 5.023 0 0 0 0-2zm-.728 3.757l1.485 1.486a1 1 0 1 1-1.414 1.414l-1.486-1.485a5.027 5.027 0 0 0 1.415-1.415zM9 12.9V15a1 1 0 0 1-2 0v-2.1a5.023 5.023 0 0 0 2 0zm-3.757-.728l-1.486 1.485a1 1 0 0 1-1.414-1.414l1.485-1.486a5.027 5.027 0 0 0 1.415 1.415zM3.1 9H1a1 1 0 1 1 0-2h2.1a5.023 5.023 0 0 0 0 2zM8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" fill-rule="evenodd"></path></svg>
                                    </div>
                                </label>

                                <script>
                                    $(function() {
                                        $('[name=dark_mode]').on('change', function() {
                                            body = $(this).closest('body');
                                            value = $(this).val();
                    
                                            if (value == '0') {
                                                body.removeClass('dark');
                                            } else {
                                                body.addClass('dark');
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 color-box">
                            <label class="mb-2">{{ trans('messages.color_scheme') }}</label>
                            <div class="text-left mb-4 profile-scheme-select mt-2">
                                <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.default') }}" value="default" {{ !Auth::user()->admin->getColorScheme() || Auth::user()->admin->getColorScheme() == 'default' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-default" />
                                <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.blue') }}" value="blue" {{ Auth::user()->admin->getColorScheme() == 'blue' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-blue" />
                                <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.green') }}" value="green" {{ Auth::user()->admin->getColorScheme() == 'green' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-green" />
                                <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.brown') }}" value="brown" {{ Auth::user()->admin->getColorScheme() == 'brown' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-brown" />
                                <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.pink') }}" value="pink" {{ Auth::user()->admin->getColorScheme() == 'pink' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-pink" />
                                <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.grey') }}" value="grey" {{ Auth::user()->admin->getColorScheme() == 'grey' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-grey" />
                                <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.white') }}" value="white" {{ Auth::user()->admin->getColorScheme() == 'white' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-white" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 color-box">
                            @include('helpers.form_control', [
                                'type' => 'select',
                                'class' => '',
                                'name' => 'text_direction',
                                'value' => $admin->text_direction,
                                'help_class' => 'admin',
                                'options' => [
                                    ['text' => trans('messages.text_direction.ltr'), 'value' => 'ltr'],
                                    ['text' => trans('messages.text_direction.rtl'), 'value' => 'rtl']
                                ],
                                'rules' => '',
                            ])
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="sub_section">
                    <h3 class="text-semibold text-primary mb-4">{{ trans('messages.account') }}</h3>

                    @include('helpers.form_control', ['type' => 'text', 'name' => 'email', 'value' => $user->email, 'help_class' => 'profile', 'rules' => $user->rules()])

                    @include('helpers.form_control', ['type' => 'password', 'label'=> trans('messages.new_password'), 'name' => 'password', 'rules' => $user->rules()])

                    @include('helpers.form_control', ['type' => 'password', 'name' => 'password_confirmation', 'rules' => $user->rules()])

                </div>
            </div>
        </div>
        <hr>
        <div class="text-left">
            <button class="btn btn-secondary"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
        </div>

    <form>

    <script>
        function changeSelectColor() {
            $('.select2 .select2-selection__rendered, .select2-results__option').each(function() {
                var text = $(this).html();
                if (text == '{{ trans('messages.default') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='material-icons-round text-teal-600 theme-option me-2'>padding</i>");
                    }
                }
                if (text == '{{ trans('messages.blue') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='material-icons-round text-blue theme-option me-2'>padding</i>");
                    }
                }
                if (text == '{{ trans('messages.green') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='material-icons-round text-green theme-option me-2'>padding</i>");
                    }
                }
                if (text == '{{ trans('messages.brown') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='material-icons-round text-brown theme-option me-2'>padding</i>");
                    }
                }
                if (text == '{{ trans('messages.pink') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='material-icons-round text-pink theme-option me-2'>padding</i>");
                    }
                }
                if (text == '{{ trans('messages.grey') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='material-icons-round text-grey theme-option me-2'>padding</i>");
                    }
                }
                if (text == '{{ trans('messages.white') }}') {
                    if($(this).find("i").length == 0) {
                        $(this).prepend("<i class='material-icons-round text-white theme-option me-2'>padding</i>");
                    }
                }
            });
        }

        $(document).ready(function() {
            setInterval("changeSelectColor()", 100);

            // Preview upload image
            $("input.previewable").on('change', function() {
                var img = $("img[preview-for='" + $(this).attr("name") + "']");
                previewImageBrowse(this, img);
            });
            $(".remove-profile-image").click(function() {
                var img = $(this).parents(".profile-image").find("img");
                var imput = $(this).parents(".profile-image").find("input[name='_remove_image']");
                img.attr("src", img.attr("empty-src"));
                imput.val("true");
            });

            // change color effects
            $('.color-scheme-select').on('click', function(e) {
                var value = $(this).val();
                $("body").removeClass (function (index, className) {
                    return (className.match (/(^|\s)theme-\S+/g) || []).join(' ');
                });
                $('body').addClass('theme-' + value);

                // change light icon
                if (value == 'white') {
                    $('.navbar-icon img').each(function() {
                        $(this).attr('src', $(this).attr('src').replace('images/icons/SVG/', 'images/icons/dark/SVG/'));
                        if($('.default-logo').length) {
                            $('.default-logo').attr('src', $('.default-logo').attr('src').replace('logo-light.svg','logo-dark.svg'));
                        }
                    });
                } else {
                    $('.navbar-icon img').each(function() {
                        $(this).attr('src', $(this).attr('src').replace('images/icons/dark/SVG/', 'images/icons/SVG/'));
                        if($('.default-logo').length) {
                            $('.default-logo').attr('src', $('.default-logo').attr('src').replace('logo-dark.svg', 'logo-light.svg'));
                        }
                    });
                }
            });
            $('[name=menu_layout]').on('change', function(e) {
                var type = $('[name=menu_layout]:checked').val();

                $('body').removeClass('topbar');
                $('body').removeClass('leftbar');

                $('body').addClass(type + 'bar');
            });
        });
    </script>

@endsection
