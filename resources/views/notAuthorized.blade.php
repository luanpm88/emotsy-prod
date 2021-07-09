@extends('layouts.clean')

@section('title', trans('messages.not_authorized'))

@section('content')
                <div class="alert bg-danger alert-styled-left">
                    <span class="text-semibold">
                        {{ trans('messages.not_authorized_message') }}
                    </span>
                </div>
                <a href='#back' onclick='history.back()' class='btn btn-outline-secondary-400'>{{ trans('messages.go_back') }}</a>                
@endsection