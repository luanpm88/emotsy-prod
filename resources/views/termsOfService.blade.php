@extends('layouts.popup.medium')

@section('content')

    {!! Acelle\Model\Setting::get('terms_of_service.content') !!}

@endsection