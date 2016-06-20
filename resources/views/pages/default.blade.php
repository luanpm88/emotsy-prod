@extends('layouts.page')

@section('title', trans('messages.' . $page->layout->alias))

@section('content')
                {!! $page->content !!}
@endsection