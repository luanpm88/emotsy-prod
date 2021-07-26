@extends('layouts.core.backend')

@section('title', $server->name)

@section('page_header')
    @foreach ($notices as $n)
        @include('elements._notification', [
            'level' => 'warning',
            'title' => $n['title'],
            'message' => htmlspecialchars($n['message']),
        ])
    @endforeach

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("Admin\SendingServerController@index") }}">{{ trans('messages.sending_servers') }}</a></li>
            <li class="breadcrumb-item">{{ trans('messages.edit') }}</li>
        </ul>
        <h1>
            <div class="d-flex align-items-center">
                <span class="text-semibold me-3"><span class="material-icons-outlined">
    edit
    </span>
                    {{ $server->name }}
                </span>
                    
                <span class="label label-flat bg-{{$server->status}}">{{$server->status}}</span>
            </div>
        </h1>
    </div>

@endsection

@section('content')
    
    @include('admin.sending_servers.form.' . $server->type, ['identities' => $identities, 'bigNotices' => $bigNotices])

    <script>
        var SendTestEmail = {
            popup: null,
            url: '{{ action('Admin\SendingServerController@test', $server->uid) }}',
    
            getPopup: function() {
                if (this.popup == null) {
                    this.popup = new Popup({
                        url: this.url
                    });
                }
    
                return this.popup;
            }
        }
        $(function() {
            $('#SendTestEmailButton').on('click', function(e) {
                e.preventDefault();
    
                SendTestEmail.getPopup().load();
            });
        });
    </script>

    <script>
        $(function() {
            $('.switch-form-toggle').on('click', function() {
                var form = $(this).closest('form');
                var disabled = $(this).prop('data-disabled');
                var label_edit = $(this).attr('data-edit');
                var label_cancel = $(this).attr('data-cancel');
                
                if (disabled == 'false') {
                    form.find('input, select').prop('disabled', true);
                    $(this).prop('data-disabled', 'true');
                    $(this).html(label_edit);
                    form.find('.edit-group').removeClass('hide');
                    form.find('.cancel-group').addClass('hide');
                } else {
                    form.find('input, select').prop('disabled', false);
                    $(this).prop('data-disabled', 'false');
                    $(this).html(label_cancel);
                    form.find('.edit-group').addClass('hide');
                    form.find('.cancel-group').removeClass('hide');
                }
            });
        });
    </script>
@endsection
