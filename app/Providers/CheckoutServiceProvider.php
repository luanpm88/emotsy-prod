<?php

namespace Acelle\Providers;

use Illuminate\Support\ServiceProvider;
use Acelle\Cashier\Services\StripePaymentGateway;
use Acelle\Cashier\Services\OfflinePaymentGateway;
use Acelle\Cashier\Services\BraintreePaymentGateway;
use Acelle\Cashier\Services\CoinpaymentsPaymentGateway;
use Acelle\Cashier\Services\PaystackPaymentGateway;
use Acelle\Cashier\Services\PaypalPaymentGateway;
use Acelle\Cashier\Services\RazorpayPaymentGateway;
use Acelle\Model\Setting;
use Acelle\Library\Facades\Hook;
use Acelle\Library\Facades\Billing;

class CheckoutServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Only bootstraping the services if the application is already initialized
        if (!isInitiated()) {
            return;
        }

        // register gateways
        $paymentInstruction = Setting::get('cashier.offline.payment_instruction');
        $offline = new OfflinePaymentGateway($paymentInstruction);
        Billing::register($offline);

        $publishableKey = Setting::get('cashier.stripe.publishable_key');
        $secretKey = Setting::get('cashier.stripe.secret_key');
        $stripe = new StripePaymentGateway($publishableKey, $secretKey);
        Billing::register($stripe);

        $environment = Setting::get('cashier.braintree.environment');
        $merchantId = Setting::get('cashier.braintree.merchant_id');
        $publicKey = Setting::get('cashier.braintree.public_key');
        $privateKey = Setting::get('cashier.braintree.private_key');
        $braintree = new BraintreePaymentGateway($environment, $merchantId, $publicKey, $privateKey);
        Billing::register($braintree);

        $merchantId = Setting::get('cashier.coinpayments.merchant_id');
        $publicKey = Setting::get('cashier.coinpayments.public_key');
        $privateKey = Setting::get('cashier.coinpayments.private_key');
        $ipnSecret = Setting::get('cashier.coinpayments.ipn_secret');
        $receiveCurrency = Setting::get('cashier.coinpayments.receive_currency');
        $coinpayments = new CoinpaymentsPaymentGateway($merchantId, $publicKey, $privateKey, $ipnSecret, $receiveCurrency);
        Billing::register($coinpayments);

        $publicKey = Setting::get('cashier.paystack.public_key');
        $secretKey = Setting::get('cashier.paystack.secret_key');
        $paystack = new PaystackPaymentGateway($publicKey, $secretKey);
        Billing::register($paystack);

        $environment = Setting::get('cashier.paypal.environment');
        $clientId = Setting::get('cashier.paypal.client_id');
        $secret = Setting::get('cashier.paypal.secret');
        $paypal = new PaypalPaymentGateway($environment, $clientId, $secret);
        Billing::register($paypal);

        $keyId = Setting::get('cashier.razorpay.key_id');
        $keySecret = Setting::get('cashier.razorpay.key_secret');
        $razorpay = new RazorpayPaymentGateway($keyId, $keySecret);
        Billing::register($razorpay);
    }
}
