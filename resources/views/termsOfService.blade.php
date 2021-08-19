@extends('layouts.popup.medium')

@section('content')

    {!! Acelle\Model\Setting::get('terms_of_service.content') !!}

    <hr>

    <div class="text-center">
        <button class="btn btn-secondary px-4 close">{{ trans('messages.tos.i_agree') }}</button>
    </div>
@endsection