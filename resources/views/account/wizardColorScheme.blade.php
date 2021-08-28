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
                    @include('layouts.core._theme_mode_control', [
                        'theme_mode' => Auth::user()->customer->theme_mode,
                    ])
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