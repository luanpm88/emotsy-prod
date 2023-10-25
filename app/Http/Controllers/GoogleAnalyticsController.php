<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Library\Facades\Billing;
use Laravel\Socialite\Facades\Socialite;
use Google\Client;

class GoogleAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user()->customer;

        return view('google_analytics.index', [
            'customer' => $customer,
        ]);
    }

    public function redirectToGoogle(Request $request)
    {
        if ($request->redirect) {
            session()->put('google-redirect', $request->redirect);
        } else {
            session()->forget('google-redirect');
        }

        return Socialite::driver('google')
            ->stateless()
            ->with(['redirect_uri' => action('GoogleAnalyticsController@redirect')])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $customer = $request->user()->customer;
        $user = Socialite::driver('google')->user();
        $customer->google_auth = json_encode($request->all());
        $customer->save();

        // You can store user details in your database or retrieve Google Analytics data here.

        // Redirect to a page that displays Google Analytics data.
    }

    public function redirect(Request $request)
    {
        $customer = $request->user()->customer;
        // $customer->google_auth = json_encode($request->all());

        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(action('GoogleAnalyticsController@redirect'));

        // Exchange the authorization code for an access token and refresh token
        $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));

        // save
        $customer->google_auth = $accessToken;
        $customer->save();

        // You can store user details in your database or retrieve Google Analytics data here.

        // Redirect to a page that displays Google Analytics data.

        if (session()->has('google-redirect')) {
            $url = session()->get('google-redirect');
            session()->forget('google-redirect');
            return redirect()->away($url);
        } else {
            return redirect()->action('GoogleAnalyticsController@index');
        }
    }
}