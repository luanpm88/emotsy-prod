@extends('layouts.page')

@section('title', trans('messages.' . $page->layout->alias))

@section('content')

                <form action="{{ action('PageController@sign_up_form', $list->uid) }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
                    
                    {!! $page->content !!}
                
                </form>
@endsection