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
            <div class="list-box bg-{{ $gateway['name'] }}">
                <div>
                    <label>{{ trans('messages.payments.' . $name) }}</label>
                    <p>{{ trans('messages.payments.' . $name . '.list_intro') }}</p>
                    <div class="list-box-footer">
                        @if (\Acelle\Model\Setting::get('system.payment_gateway') == $name)
                            <span class="label label-info mt-5 bg-primary">{{ trans('messages.payment.primary') }}</span>
                        @endif
                        <a class="btn btn-mc_inline pull-right" href="{{ action('Admin\PaymentController@edit', $name) }}">
                            {{ trans('messages.payment.edit') }}
                        </a>
                        
                        @if (\Acelle\Model\Setting::get('system.payment_gateway') !== $name)
                            <a class="btn btn-mc_default pull-right" link-method="POST" href="{{ action('Admin\PaymentController@setPrimary', $name) }}">
                                {{ trans('messages.payment.set_primary') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
				
@endsection
