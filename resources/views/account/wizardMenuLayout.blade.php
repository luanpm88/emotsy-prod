@extends('layouts.popup.small')

@section('bar-title')
    {{ trans('messages.account.welcome_user', [
        'name' => Auth::user()->displayName(),
    ]) }}
@endsection

@section('content')
    <form id="wizardColorScheme" class="" action="{{ action('AccountController@wizardMenuLayout') }}"
        method="POST">
        {{ csrf_field() }}

        <h2 class="mt-0 text-center">{{ trans('messages.account.menu_layout') }}</h2>

        <div class="text-center my-4">
            <div>
                <label class="layout-select me-4 position-relative">
                    <input style="position: absolute;
                    z-index: 1;
                    top: 70px;
                    left: 155px;" type="radio" name="menu_layout" value="top" {{ request()->user()->customer->menu_layout == 'top' || request()->user()->customer->menu_layout == 'none' ? 'checked' : '' }} class="styled mr-2" />
                    <img src="{{ url('images/layout-menu-top.svg') }}" width="180px" />
                </label>
                <label class="layout-select position-relative">
                    <input style="position: absolute;
                    z-index: 1;
                    top: 70px;
                    left: 155px;" type="radio" name="menu_layout" value="left" {{ request()->user()->customer->menu_layout == 'left' ? 'checked' : '' }} class="styled mr-2"  />
                    <img src="{{ url('images/layout-menu-left.svg') }}" width="180px" />
                </label>
            </div>
        </div>

        
        <p class="text-center">
            {{ trans('messages.account.menu_layout.into') }}
        </p>
        <div class="mt-5 text-center">
            <button class="btn btn-primary px-4">{{ trans('messages.ok') }}</button>
        </div>
    </form>

    <script>
        $(function() {
            $('#wizardColorScheme').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    method: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize()
                })
                .done(function( response ) {
                    window.location.reload();
                });
            });

            $('#wizardColorScheme [name=menu_layout]').on('change', function(e) {
                var type = $('#wizardColorScheme [name=menu_layout]:checked').val();

                $('body').removeClass('topbar');
                $('body').removeClass('leftbar');

                $('body').addClass(type + 'bar');
            });
        });
    </script>
@endsection