<?php

namespace Acelle\Http\Controllers\Api\Public;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

/**
 * /api/v1/payment - API controller for managing subscriptions.
 */
class EmailVerificationController extends Controller
{
    /**
     * Get all payment.
     *
     * GET /api/v1/email-verification/checkout
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        // random email
        // $email = $request->email;
        $email = uniqId() . "@gmail.com";

        $user = \Acelle\Model\User::where('email', $email)->first();

        // Create new customer
        if (!$user) {
            // Create new customer
            $customer = \Acelle\Model\Customer::newCustomer();
            $customer->timezone = config('app.timezone');
            $customer->language_id = \Acelle\Model\Language::getDefaultLanguage()->id;
            $customer->save();

            $user = new \Acelle\Model\User();
            $user->email = $email;
            $user->activated = true;
            $user->customer_id = $customer->id;
            $user->password = bcrypt('12345678');

            $user->save();
        } else {
            $customer = $user->customer;
        }

        // Assign Free Plan
        $plan = \Acelle\Model\PlanGeneral::first();

        try {
            $subscription = $customer->assignGeneralPlan($plan);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

        // payment method
        $customer->updatePaymentMethod([
            'method' => $request->payment_method,
        ]);

        // init invoice
        $initInvoice = $subscription->getItsOnlyUnpaidInitInvoice();

        // checkout url
        $checkoutUrl = $customer->getPreferredPaymentGateway()->getCheckoutUrl($initInvoice);

        //
        \Auth::login($user);

        // Return checkout url
        return response()->json([
            'checkout_url' => action('\Acelle\Http\Controllers\EmailVerificationController@redirect', [
                'api_token' => $user->api_token,
                'checkout_url' => $checkoutUrl,
                'return_url' => $request->return_url,
            ]),
        ]);
    }

    public function redirect(Request $request, $api_token)
    {
        $user = \Acelle\Model\User::where('api_token', $api_token)->first();

        \Auth::login($user);

        // Billing return url
        $returnUrl = $this->addUrlParams($request->return_url, [
            'api_token' => $request->api_token,

        ]);
        \Acelle\Library\Facades\Billing::setReturnUrl($returnUrl);

        // checkout url
        // return redirect()->away($request->checkout_url);
        var_dump($request->checkout_url);
    }

    public function addUrlParams($url, $params)
    {
        // Parse the URL into components
        $parsed_url = parse_url($url);
    
        // Extract the existing query parameters (if any)
        $existing_params = [];
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $existing_params);
        }
    
        // Merge existing and new parameters
        $merged_params = array_merge($existing_params, $params);
    
        // Build the new query string
        $new_query = http_build_query($merged_params);
    
        // Rebuild the URL with the new query string
        $new_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];
    
        // Include the port if specified
        if (isset($parsed_url['port'])) {
            $new_url .= ':' . $parsed_url['port'];
        }
    
        // Include the path if specified
        if (isset($parsed_url['path'])) {
            $new_url .= $parsed_url['path'];
        }
    
        // Append the new query string
        if ($new_query) {
            $new_url .= '?' . $new_query;
        }
    
        return $new_url;
    }
}
