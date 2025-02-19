<?php

namespace Acelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Customer;
use Acelle\Model\Language;
use Acelle\Library\Facades\SubscriptionFacade;
use Acelle\Model\SubscriptionLog;
use Acelle\Library\TransactionResult;
use Acelle\Library\Facades\Hook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Acelle\Model\User;

/**
 * /api/v1/customers - API controller for managing customers.
 */
class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->api_token;

        return response()->json(['api_token' => $token], 200);
    }
}
