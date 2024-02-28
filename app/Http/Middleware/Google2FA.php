<?php

namespace Acelle\Http\Middleware;

use Closure;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class Google2FA
{
    public function handle($request, Closure $next)
    {
        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            // return $next($request);
        }

        // register key if not exists
        if (!$request->user()->google_2fa_secret_key) {
            return redirect()->action('Google2FAController@generateSecretKey');
        }

        return redirect()->action('Google2FAController@enterCode');
    }
}
