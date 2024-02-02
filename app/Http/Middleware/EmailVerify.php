<?php

namespace Acelle\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // save previous url
        session(['2fa.redirect' => url()->full()]);

        // if (Auth::user()->two_factor_verified == false) {
        if ($this->shouldProvideTwoFactorChallenge($request)) {
            return redirect(action('UserController@verifySelectMethod'));
        }

        return $next($request);
    }

    protected function shouldProvideTwoFactorChallenge(Request $request): bool
    {
        return $request->user() &&
            !$request->session()->has('two_factor_authenticated');
    }
}
