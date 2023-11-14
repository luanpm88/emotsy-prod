<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;
use Acelle\Library\Facades\Billing;
use Laravel\Socialite\Facades\Socialite;
use Google\Client;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_GetReportsRequest;

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

        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(action('GoogleAnalyticsController@redirect'));
        $client->addScope('https://www.googleapis.com/auth/analytics.readonly');

        return redirect($client->createAuthUrl());

        // return Socialite::driver('google')
        //     ->scopes(['profile', 'email'])
        //     ->stateless()
        //     ->with(['redirect_uri' => action('GoogleAnalyticsController@redirect')])
        //     ->redirect();
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

    // campaign google analytics report
    public function googleAnalytics(Request $request)
    {
        $campaign = \Acelle\Model\Campaign::findByUid($request->uid);

        // Get Google Client
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($request->user()->customer->getGoogleAccessToken());

        // 
        $analytics = new Google_Service_AnalyticsReporting($client);

        // Create the DateRange object.
        $dateRange = new Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("2015-06-15");
        $dateRange->setEndDate("2015-06-30");

        // Create the Metrics object.
        $sessions = new Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("sessions");

        //Create the Dimensions object.
        $browser = new Google_Service_AnalyticsReporting_Dimension();
        $browser->setName("ga:browser");

        // Create the ReportRequest object.
        $request = new Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId("XXXX");
        $request->setDateRanges($dateRange);
        $request->setDimensions(array($browser));
        $request->setMetrics(array($sessions));

        $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests( array( $request) );
        return $analytics->reports->batchGet( $body );

        return view('campaigns.googleAnalytics', [
            'campaign' => $campaign,
        ]);
    }
}