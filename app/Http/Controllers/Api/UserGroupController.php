<?php

namespace Acelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

/**
 * /api/v1/user-groups - API controller for managing user groups.
 */
class UserGroupController extends Controller
{
    /**
     * Display all user groups.
     *
     * GET /api/v1/user_groups
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::guard('api')->user();
        
        // authorize
        if (!$user->can('readAll', new \Acelle\Model\UserGroup())) {
            return \Response::json(array('message' => 'Unauthorized'), 401);
        }
        
        $user_groups = \Acelle\Model\UserGroup::getAll()
            ->select('id', 'name', 'frontend_access', 'backend_access', 'custom_order', 'created_at', 'updated_at')
            ->get();

        return \Response::json($user_groups, 200);
    }

    /**
     * Display the specified user group information.
     *
     * GET /api/v1/user_groups/{id}
     *
     * @param int $id user groups's id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \Auth::guard('api')->user();

        $user_group = \Acelle\Model\UserGroup::find($id);

        // authorize
        if (!$user->can('read', $user_group)) {
            return \Response::json(array('message' => 'Unauthorized'), 401);
        }

        // check if user group exists
        if (!is_object($user_group)) {
            return \Response::json(array('message' => 'Item not found'), 404);
        }

        // user group info
        $user_group = [
            'id' => $user_group->id,
            'name' => $user_group->name,
            'frontend_access' => $user_group->frontend_access,
            'backend_access' => $user_group->backend_access,
            'options' => $user_group->getOptions(),
            'user_id' => $user_group->user_id,
            'users_count' => $user_group->users()->count(),
            'created_at' => $user_group->created_at,
            'updated_at' => $user_group->updated_at,
        ];

        return \Response::json($user_group, 200);
    }
    
    /**
     * Create new user group.
     *
     * POST /api/v1/user_groups
     *
     * @param \Illuminate\Http\Request $request All user group information.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::guard('api')->user();
        $user_group = new \Acelle\Model\UserGroup();

        // authorize
        if (!$user->can('create', $user_group)) {
            return \Response::json(array('message' => 'Unauthorized'), 401);
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $validator = \Validator::make($request->all(), \Acelle\Model\UserGroup::apiRules());
            
            if ($validator->fails()) {
                return response()->json($validator->messages(), 403);
            }
            
            $user_group->fill($request->all());
            $user_group->updateOptions($request->options);
            $user_group->user_id = $user->id;
            $user_group->save();
            
            if (isset($request->sending_servers)) {
                $user_group->updateSendingServers($request->sending_servers);
            }
            
            return \Response::json(array(
                'message' => trans('messages.user_group.created'),
                'user_group_id' => $user_group->id
            ), 200);
        }
    }
}
