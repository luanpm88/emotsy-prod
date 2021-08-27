@extends('layouts.popup.small')

@section('bar-title')
    {{ trans('messages.account.welcome_user', [
        'name' => Auth::user()->displayName(),
    ]) }}
@endsection

@section('content')
    <form id="wizardColorScheme" class="" action="{{ action('AccountController@wizardColorScheme') }}"
        method="POST">
        {{ csrf_field() }}
        <div class="text-center my-4">
            <img width="100px" src="{{ url('images/pantone.svg') }}" />
        </div>
        <h2 class="mt-0 text-center">{{ trans('messages.account.color_scheme') }}</h2>
        <p class="text-center">
            {{ trans('messages.account.color_scheme.into') }}
        </p>

        <div class="row">
            <div class="col-md-12" style="text-align: center">
                <label class="mb-3  small text-muted">{{ trans('messages.theme_mode') }}:</label>
                <div class="mb-4">
                    <label class="dark-mode-select me-3">
                        <input style="display:none" type="radio" name="dark_mode" value="1" {{ Auth()->user()->customer->dark_mode ? 'checked' : '' }} class="styled mr-2" />
                        <div class="theme-mode-icon dark-mode shadow">
                            <svg class="SVGInline-svg SVGInline--cleaned-svg SVG-svg DarkModeIcon-svg SVG--color-svg" style="width: 12px;height: 12px;" viewBox="0 0 17 16" xmlns="http://www.w3.org/2000/svg"><path d="M7.914 0a6.874 6.874 0 0 0-1.26 3.972c0 3.875 3.213 7.017 7.178 7.017.943 0 1.843-.178 2.668-.5C15.423 13.688 12.34 16 8.704 16 4.174 16 .5 12.41.5 7.982.5 3.814 3.754.389 7.914 0z" fill-rule="evenodd"></path></svg>
                        </div>
                    </label>
                    <label class="dark-mode-select">
                        <input style="display:none" type="radio" name="dark_mode" value="0" {{ !Auth()->user()->customer->dark_mode ? 'checked' : '' }} class="styled mr-2"  />
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

        <div class="text-center">
            <label class="mb-1 d-block small text-muted">{{ trans('messages.color_scheme') }}:</label>

            <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.default') }}" value="default" {{ !Auth::user()->customer->getColorScheme() || Auth::user()->customer->getColorScheme() == 'default' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-default" />
            <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.blue') }}" value="blue" {{ Auth::user()->customer->getColorScheme() == 'blue' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-blue" />
            <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.green') }}" value="green" {{ Auth::user()->customer->getColorScheme() == 'green' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-green" />
            <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.brown') }}" value="brown" {{ Auth::user()->customer->getColorScheme() == 'brown' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-brown" />
            <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.pink') }}" value="pink" {{ Auth::user()->customer->getColorScheme() == 'pink' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-pink" />
            <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.grey') }}" value="grey" {{ Auth::user()->customer->getColorScheme() == 'grey' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-grey" />
            <input type="radio" name="color_scheme" title="{{ trans('messages.color_scheme.white') }}" value="white" {{ Auth::user()->customer->getColorScheme() == 'white' ? 'checked' : '' }} class="xtooltip color-scheme-select scheme-white" />
        </div>
        <div class="mt-5 text-center">
            <button class="btn btn-primary px-4">{{ trans('messages.next') }}</button>
        </div>
    </form>

    <script>
        $(function() {
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
            
            $('#wizardColorScheme').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    method: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize()
                })
                .done(function( response ) {
                    wizardUserPopup.loadHtml(response);
                });
            });
        });
    </script>
@endsection