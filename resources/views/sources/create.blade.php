@extends('layouts.frontend')

@section('title', trans('messages.stores_connections'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li><a href="{{ action("SourceController@index") }}">{{ trans('messages.stores_connections') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><i class="icon-plus-circle2"></i> {{ trans('messages.source.add_new') }}</span>
		</h1>
	</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <p>{{ trans('messages.source.select_source_type.wording') }}</p>
            <div class="source_list">
                <a class="source_type" href="{{ $lazadaConnectLink }}">
                    <img width="200px" src="{{ url('images/lazada.png') }}" />
                </a>
            </div>
        </div>
    </div>
@endsection
