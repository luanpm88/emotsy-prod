<?php

namespace Acelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

/**
 * /api/v1/customers - API controller for managing customers.
 */
class CustomerController extends Controller
{
    /**
     * Create new customer.
     *
     * POST /api/v1/customers/store
     *
     * @param \Illuminate\Http\Request $request All customer information.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get current user
        $current_user = \Auth::guard('api')->user();
        $customer = new \Acelle\Model\Customer();

        // authorize
        if (!$current_user->can('create', $customer)) {
            return \Response::json(array('message' => 'Unauthorized'), 401);
        }

        // save posted data
        if ($request->isMethod('post')) {
            $rules = $customer->apiRules();
            
            $validator = \Validator::make($request->all(), $rules);    
            if ($validator->fails()) {
                return response()->json($validator->messages(), 403);
            }
            
            // Create user account for customer
            $user = new \Acelle\Model\User();
            $user->email = $request->email;
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }            
            $user->save();

            // Save current user info
            $customer->user_id = $user->id;
            $customer->admin_id = $current_user->admin->id;
            $customer->fill($request->all());
            $customer->status = 'active';
            
            if ($customer->save()) {
                // Upload and save image
                if ($request->hasFile('image')) {
                    if ($request->file('image')->isValid()) {
                        // Remove old images
                        $customer->removeImage();
                        $customer->image = $customer->uploadImage($request->file('image'));
                        $customer->save();
                    }
                }

                // Remove image
                if ($request->_remove_image == 'true') {
                    $customer->removeImage();
                    $customer->image = '';
                }

                return \Response::json(array(
                    'message' => trans('messages.customer.created'),
                    'customer_uid' => $customer->uid
                ), 200);
            }
        }
    }
}
