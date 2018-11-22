@extends('layouts.backend')

@section('title', trans('messages.payment_gateways'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('assets/js/core/libraries/jquery_ui/touch.min.js') }}"></script>
		
	<script type="text/javascript" src="{{ URL::asset('js/listing.js') }}"></script>		
@endsection

@section('page_header')

    <div class="page-title">				
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-credit-card2"></i> {{ trans('messages.payment_gateways') }}</span>
        </h1>				
    </div>

@endsection

@section('content')
	
    <p>{{ trans('messages.payment_gateways.wording') }}</p>
    
    <div class="list-boxes">
        @foreach ($gateways as $name => $gateway)
            <div class="list-box {{ trans('messages.payment.status.' . $gateway['status']) }}">
                <a href="{{ action('Admin\PaymentController@edit', $name) }}">
                    <label>{{ trans('messages.payments.' . $name) }}</label>
                    <p>{{ trans('messages.payments.' . $name . '.list_intro') }}</p>
                    <div class="list-box-footer">
                        <div><span class="label label-info bg-{{ trans('messages.payment.status.' . $gateway['status']) }}">{{ trans('messages.payment.status.' . $gateway['status']) }}</span></div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
				
@endsection
