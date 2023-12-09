<?php

namespace Acelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;
use Acelle\Library\Facades\SubscriptionFacade;
use Acelle\Model\SubscriptionLog;
use Acelle\Library\TransactionResult;

/**
 * /api/v1/users - API controller for managing users.
 */
class UserController extends Controller
{
    /**
     * Generate one time token.
     *
     * PATCH /api/v1/users/{uid}/login-token
     *
     * @param int $id user's uid
     *
     * @return \Illuminate\Http\Response
     */
    public function loginToken(Request $request)
    {
        $user = \Auth::guard('api')->user();

        $user->generateOneTimeToken();

        echo json_encode(array(
            'token' => $user->one_time_api_token,
            'url' => action('Controller@tokenLogin', ['token' => $user->one_time_api_token]),
        ), JSON_UNESCAPED_SLASHES);
    }
}
