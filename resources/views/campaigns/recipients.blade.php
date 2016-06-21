@extends('layouts.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.recipients'))
	
@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
		
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')
	
			<div class="page-title">
				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
					<li><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
                    <li class="active">{{ $campaign->name }}</li>
				</ul>
				<h1>
					<span class="text-semibold"><i class="icon-users4"></i> {{ trans('messages.recipients') }}</span>
				</h1>
			</div>

@endsection

@section('content')
                <form action="{{ action('CampaignController@recipients', $campaign->uid) }}" method="POST" class="form-validate-jqueryz">
					{{ csrf_field() }}
					
		
					
					<div class="text-right">
						<button class="btn bg-teal mr-10">{{ trans('messages.next') }} <i class="icon-arrow-right7"></i> </button>
					</div>
					
				<form>
					
				
@endsection
