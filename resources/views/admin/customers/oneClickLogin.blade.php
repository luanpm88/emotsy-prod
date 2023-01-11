@extends('layouts.popup.large')

@section('bar-title')
    {{ trans('messages.admin.one_cick_login') }}
@endsection

@section('footer')
    <div class="text-end">
        <button class="btn btn-default close" type="button">
            {{ trans('messages.close') }}
        </button>
    </div>
@endsection

@section('content')
    
    <div id="OneClickLoginContainer" class="d-flex align-items-center justify-content-center">
        <div class="border rounded me-2 bg-light" style="padding: 6px 8px;">
            <span>{{ $url }}</span>
        </div>
        <div>
            <button id="CopyOneClickLoginUrl" class="btn btn-secondary" type="button">
                <span class="material-symbols-rounded">
                    content_copy
                    </span> {{ trans('messages.admin.one_cick_login.copy') }}
            </button>
        </div>
    </div>

    <script>
        $(function() {
            $('#CopyOneClickLoginUrl').on('click', function() {
                copyToClipboard('{{ $url }}', $('#OneClickLoginContainer'));
                notify('success', '{{ trans('messages.notify.success') }}', '{{ trans('messages.admin.one_cick_login.copied') }}');
            });
        })
    </script>
@endsection 