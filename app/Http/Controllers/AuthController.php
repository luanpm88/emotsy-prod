<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Acelle\Library\Log as MailLog;
use Acelle\Model\Customer;
use Acelle\Model\User;
use Acelle\Library\Facades\Hook;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Google OAuth 2.0.
     */
    public function getGoogleOAuthProvider()
    {
        $config = [
            'client_id' => \Acelle\Model\Setting::get('oauth.google_client_id'),
            'client_secret' => \Acelle\Model\Setting::get('oauth.google_client_secret'),
            'redirect' => action('AuthController@googleCallback'),
        ];

        $provider = Socialite::buildProvider(
            \Laravel\Socialite\Two\GoogleProvider::class, 
            $config
        );
        
        return $provider;
    }
    
    /**
     * Google OAuth 2.0.
     */
    public function googleRedirect(Request $request)
    {
        return $this->getGoogleOAuthProvider()->redirect();
    }

    /**
     * Google OAuth 2.0.
     */
    public function googleCallback(Request $request)
    {
        $googleUser = $this->getGoogleOAuthProvider()->user();
 
        $user = User::where('google_id', $googleUser->id)->first();
    
        if ($user) {
            $user->update([
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);
        } else {
            // check if email exists
            if (User::where('email', $googleUser->email)->first()) {
                return redirect('/login')->with('alert-error', trans('messages.google.auth.email_exist', [
                    'email' => $googleUser->email,
                ]));
            }

            \Acelle\Model\User::createCustomer([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);

            $user = User::where('google_id', $googleUser->id)->first();
        }
    
        // login
        \Auth::login($user);
        return redirect()->action('HomeController@index');
    }
}
