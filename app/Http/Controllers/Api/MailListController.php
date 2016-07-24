<?php

namespace Mailixa\Http\Controllers\Api;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;
use Mailixa\Http\Controllers\Controller;

/**
* /api/v1/lists - API controller for managing lists.
* 
*/
class MailListController extends Controller
{
    /**
     * Display all user's lists.
     *
     * GET /api/v1/lists
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Auth::guard('api')->user();
        
        $lists = \Mailixa\Model\MailList::getAll()
            ->select("uid", "name", "default_subject", "from_email", "from_name", "status", "created_at", "updated_at")
            ->where("user_id", "=", $user->id)
            ->get();
        
        return \Response::json($lists, 200);
    }
    
    /**
     * Display the specified list information.
     *
     * GET /api/v1/lists/{id}
     *
     * @param  int  $id     List's id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \Auth::guard('api')->user();
        
        $item = \Mailixa\Model\MailList::
            where("uid", "=", $id)
            ->first();

        // authorize
        if (!$user->can('read', $item)) {
            return \Response::json(array("message"=>"Unauthorized"), 401);
        }
        
        // check if item exists
        if (!is_object($item)) {
            return \Response::json(array("message"=>"Item not found"), 404);
        }
        
        // list info
        $list = [
            'uid' => $item->uid,
            'name' => $item->name,
            'default_subject' => $item->default_subject,
            'from_email' => $item->from_email,
            'from_name' => $item->from_name,
            'remind_message' => $item->remind_message,
            'status' => $item->status,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
        ];
        
        // Contact information
        $contact = null;
        if(isset($item->contact)) {
            // contact information
            $contact = [
                'company' => $item->contact->company,
                'address_1' => $item->contact->address_1,
                'address_2' => $item->contact->address_2,
                'country' => $item->contact->country->name,
                'state' => $item->contact->state,
                'zip' => $item->contact->zip,
                'phone' => $item->contact->phone,
                'url' => $item->contact->url,
                'email' => $item->contact->email,
                'city' => $item->contact->city,
            ];
        }
        
        // statistics
        $statistics = [
            'open_uniq_rate' => $item->openUniqRate(),
            'click_rate' => $item->clickRate(),
            'subscribe_rate' => $item->subscribeRate(),
            'unsubscribe_rate' => $item->unsubscribeRate(),
            'unsubscribe_count' => $item->unsubscribeCount(),
            'unconfirmed_count' => $item->unconfirmedCount(),
        ];
        
        return \Response::json(['list' => $list, 'contact' => $contact, 'statistics' => $statistics], 200);
    }
}
