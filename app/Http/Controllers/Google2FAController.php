<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;
use Google2FA;

class Google2FAController extends Controller
{
    public function check(Request $request)
    {

    }

    public function enterCode()
    {
        return view('google2fa.enterCode');
    }

    public function generateSecretKey(Request $request)
    {
        $key = Google2FA::generateSecretKey();
        $inlineImageUrl = Google2FA::getQRCodeInline(
            $request->user()->name,
            $request->user()->email,
            $key
        );
        return view('google2fa.generateSecretKey', [
            'key' => $key,
            'inlineImageUrl' => $inlineImageUrl,
        ]);
    }

    public function saveKey(Request $request)
    {
        $user = $request->user();
        $user->google_2fa_secret_key = $request->key;
        $user->save();
    }
}
