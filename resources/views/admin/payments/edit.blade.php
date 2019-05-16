@extends('layouts.backend')

@section('title', trans('messages.payments.' . $gateway['name']))

@section('page_script')
	<script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li><a href="{{ action("Admin\PaymentController@index") }}">{{ trans('messages.payment_gateways') }}</a></li>
            <li class="active">{{ trans('messages.update') }}</li>
        </ul>
        <h1>
            <span class="text-semibold"><i class="icon-credit-card2"></i> {{ trans('messages.payments.' . $gateway['name']) }}</span>
        </h1>
    </div>

@endsection

@section('content')

    <form enctype="multipart/form-data" action="{{ action('Admin\PaymentController@update', $gateway['name']) }}" method="POST" class="form-validate-jquery">
        {{ csrf_field() }}

		<div class="row">
			<div class="col-md-6">
				<p>
					{!! trans('messages.payment.' . $gateway['name'] . '.wording') !!}
				</p>
			</div>
		</div>

        @if (!empty($gateway['fields']))
            <h3>{{ trans('messages.payment.options') }}</h3>
            <div class="row">
				<div class="col-md-6">
					@foreach ($gateway['fields'] as $name => $field)
						@if ($name == 'notice')
							@include('helpers.form_control', [
								'type' => 'textarea',
								'name' => 'options['.$name.']',
								'value' => $field,
								'label' => trans('messages.payment.' . $gateway['name'] . '.' . $name),
								'help_class' => 'payment',
								'rules' => ['options.' . $name => 'required'],
							])
						@else
							@include('helpers.form_control', [
								'type' => 'text',
								'name' => 'options['.$name.']',
								'value' => $field,
								'label' => trans('messages.payment.' . $gateway['name'] . '.' . $name),
								'help_class' => 'payment',
								'rules' => ['options.' . $name => 'required'],
							])
						@endif
					@endforeach
				</div>
            </div>
        @endif
        <hr>
        <div class="text-left">
            <button class="btn btn-mc_primary mr-10">{{ trans('messages.save') }}</button>
			@if (\Acelle\Model\Setting::get('system.payment_gateway') !== $gateway['name'])
				<input type="submit" class="btn btn-mc_primary bg-teal" name="save_and_set_primary" value="{{ trans('messages.save_and_set_primary') }}" />
			@endif
        </div>
    <form>

@endsection