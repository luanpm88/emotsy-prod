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
        <div class="text-center">
            <input type="radio" name="color_scheme" value="default" {{ !Auth::user()->customer->getColorScheme() || Auth::user()->customer->getColorScheme() == 'default' ? 'checked' : '' }} class="color-scheme-select scheme-default" />
            <input type="radio" name="color_scheme" value="blue" {{ Auth::user()->customer->getColorScheme() == 'blue' ? 'checked' : '' }} class="color-scheme-select scheme-blue" />
            <input type="radio" name="color_scheme" value="green" {{ Auth::user()->customer->getColorScheme() == 'green' ? 'checked' : '' }} class="color-scheme-select scheme-green" />
            <input type="radio" name="color_scheme" value="brown" {{ Auth::user()->customer->getColorScheme() == 'brown' ? 'checked' : '' }} class="color-scheme-select scheme-brown" />
            <input type="radio" name="color_scheme" value="pink" {{ Auth::user()->customer->getColorScheme() == 'pink' ? 'checked' : '' }} class="color-scheme-select scheme-pink" />
            <input type="radio" name="color_scheme" value="grey" {{ Auth::user()->customer->getColorScheme() == 'grey' ? 'checked' : '' }} class="color-scheme-select scheme-grey" />
            {{-- <input type="radio" name="color_scheme" value="white" {{ Auth::user()->customer->getColorScheme() == 'white' ? 'checked' : '' }} class="color-scheme-select scheme-white" /> --}}
        </div>
        <div class="mt-5 text-center">
            <button class="btn btn-primary px-4">{{ trans('messages.next') }}</button>
        </div>
    </form>

    <script>
        $(function() {
            $('.color-scheme-select').on('click', function(e) {
                $("body").removeClass (function (index, className) {
                    return (className.match (/(^|\s)theme-\S+/g) || []).join(' ');
                });
                $('body').addClass('theme-' + $(this).val());
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