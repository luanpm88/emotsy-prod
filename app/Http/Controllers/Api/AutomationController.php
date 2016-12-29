<?php

namespace Acelle\Http\Controllers\Api;

use Acelle\Http\Controllers\Controller;

/**
 * /api/v1/campaigns - API controller for managing campaigns.
 */
class AutomationController extends Controller
{
    /**
     * Call api for automation api call type.
     *
     * GET /api/v1/campaigns
     *
     * @return \Illuminate\Http\Response
     */
    public function apiCall($uid)
    {
        $user = \Auth::guard('api')->user();
        $automation = \Acelle\Model\Automation::findByUid($uid);
        
        // trigger api call
        // $automation->setRunning();
        return \Response::json(array('message' => trans('messages.automation.updated')), 200);
        
        // authorize
        if (!$user->can('update', $automation)) {
            return \Response::json(array('message' => 'Unauthorized'), 401);
        }
    }
}
