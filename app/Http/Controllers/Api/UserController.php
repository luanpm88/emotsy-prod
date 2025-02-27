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
    /**
     * Generate one time token.
     *
     * PATCH /api/v1/customers/{uid}/login-token
     *
     * @param int $id customer's uid
     *
     * @return \Illuminate\Http\Response
     */
    public function loginToken(Request $request)
    {
        $user = \Auth::guard('api')->user();

        // if for another customer
        if ($request->customer_uid) {
            $customer = Customer::findByUid($request->customer_uid);

            // customer not found
            if (!$customer) {
                return \Response::json(array('status' => 0, 'message' => 'Customer with uid '.$request->customer_uid.' not found'), 404);
            }

            // authorize
            if (!$user->admin->can('loginAs', $customer)) {
                return \Response::json(array('status' => 0, 'message' => 'Unauthorized'), 401);
            }

            $user = $customer->getFirstUserLegacy();
        }

        $user->generateOneTimeToken();

        echo json_encode(array(
            'token' => $user->one_time_api_token,
            'url' => action('AppController@tokenLogin', ['token' => $user->one_time_api_token]),
        ), JSON_UNESCAPED_SLASHES);
    }
    
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

    public function info(Request $request)
    {
        $user = \Auth::guard('api')->user();
        
        return response()->json([
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'image_url' => $user->getProfileImageUrl(),
        ], 200);
    }
}
