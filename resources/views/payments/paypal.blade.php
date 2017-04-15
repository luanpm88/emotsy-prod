@extends('layouts.frontend')

@section('title', trans('messages.subscription'))

@section('page_script')
@endsection

@section('page_header')

	<div class="page-title">
		<ul class="breadcrumb breadcrumb-caret position-right">
			<li><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
		</ul>
		<h1>
			<span class="text-semibold"><i class="icon-quill4"></i> {{ trans('messages.your_subscriptions') }}</span>
		</h1>
	</div>

@endsection

@section('content')

	@include("account._menu")

	<div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <h2 class="text-semibold">{{ trans('messages.subscription') }}</h2>

            <div class="sub-section">
                <h3 class="text-semibold">{!! trans('messages.pay_with_paypal') !!}</h3>
				<p>
                    {!! trans('messages.purchasing_intro_' . $payment_method->type, [
                        'plan' => $subscription->plan_name,
                        'price' => Acelle\Library\Tool::format_price($subscription->price, $subscription->currency_format)
                    ]) !!}
                </p>

				@if (isset($result) && count($result->errors->deepAll()) > 0)
					<!-- Form Error List -->
					<div class="alert alert-danger alert-noborder">
						<button data-dismiss="alert" class="close" type="button"><span>×</span><span class="sr-only">Close</span></button>
						<strong>{{ trans('messages.something_error_accur') }}</strong>

						<br><br>

						<ul>
							@foreach ($result->errors->deepAll() AS $error)
							  <li>{!! $error->code . ": " . $error->message . "<br />" !!}</li>
							@endforeach
						</ul>
					</div>
				@endif


                <script src="https://www.paypalobjects.com/api/checkout.js"></script>

                <div id="paypal-button-container"></div>

                <script>

                    // Render the PayPal button

                    paypal.Button.render({

                        // Set your environment

                        env: 'sandbox', // sandbox | production

                        // PayPal Client IDs - replace with your own
                        // Create a PayPal app: https://developer.paypal.com/developer/applications/create

                        client: {
                            sandbox:    'AdGlUijDyfxH2mKbKUXtxbJqA6MXlikHYXIj3wdwq3CZirg7N72Gz0OZh12opSogVHidVsopYlaKbb3B',
                            production: 'AdGlUijDyfxH2mKbKUXtxbJqA6MXlikHYXIj3wdwq3CZirg7N72Gz0OZh12opSogVHidVsopYlaKbb3B'
                        },

                        // Wait for the PayPal button to be clicked

                        payment: function() {

                            // Make a client-side call to the REST api to create the payment

                            return paypal.rest.payment.create(this.props.env, this.props.client, {
                                transactions: [
                                    {
                                        amount: { total: '0.01', currency: 'USD' }
                                    }
                                ]
                            });
                        },

                        // Wait for the payment to be authorized by the customer

                        onAuthorize: function(data, actions) {

                            // Execute the payment
                            console.log(data.paymentID);
                            console.log(data.payerID);
                            return actions.payment.execute().then(function() {
                                document.querySelector('#paypal-button-container').innerText = 'Payment Complete!';
                            });
                        }

                    }, '#paypal-button-container');

                </script>

			</div>
		</div>
	</div>

@endsection

