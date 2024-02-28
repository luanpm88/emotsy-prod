<?php

namespace Acelle\Http\Middleware;

use Closure;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use Illuminate\Http\Request;

class TwoFA
{
    public function handle($request, Closure $next)
    {
        if (\Acelle\Model\Setting::get('2fa_enable') == 'yes') {
            // save previous url
            session(['2fa.redirect' => url()->full()]);

            // Select methos to validate
            if ($this->shouldProvideTwoFactorChallenge($request)) {
                return redirect(action('UserController@verifySelectMethod'));
            }
        }


        return $next($request);



        // return $next($request);

        // $authenticator = app(Authenticator::class)->boot($request);

        // if ($authenticator->isAuthenticated()) {
        //     // return $next($request);
        // }

        // // register key if not exists
        // if (!$request->user()->google_2fa_secret_key) {
        //     return redirect()->action('Google2FAController@generateSecretKey');
        // }

        // return redirect()->action('Google2FAController@enterCode');
    }

    protected function shouldProvideTwoFactorChallenge(Request $request): bool
    {
        return $request->user() &&
            !$request->session()->has('two_factor_authenticated');
    }
}
