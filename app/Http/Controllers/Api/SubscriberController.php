<?php

namespace Mailixa\Http\Controllers\Api;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;
use Mailixa\Http\Controllers\Controller;

class SubscriberController extends Controller
{
    /**
     * Display all list's subscribers.
     *
     * GET /api/v1/lists/{list_id}/subscribers
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  string  $list_id             List's id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $list_id)
    {
        $list = \Mailixa\Model\MailList::findByUid($list_id);
        
        if(isset($request->per_page)) {
            $per_page = $request->per_page;
        } else {
            $per_page = \Mailixa\Model\Subscriber::$itemsPerPage;
        }
        
        $items = \Mailixa\Model\Subscriber::search($request)
            ->where("mail_list_id", "=", $list->id)
            ->groupBy('subscribers.id')
            ->paginate($per_page);
        
        $subscribers = [];
        foreach ($items as $item) {
            $row = [
                "uid" => $item->uid,
                "email" => $item->email,
                "status" => $item->status,
            ];
            
            foreach ($list->fields as $field) {
                if($field->tag != 'EMAIL') {
                    $row[$field->tag] = $item->getValueByField($field);
                }
            }
            
            $subscribers[] = $row;
        }
        
        return \Response::json($subscribers, 200);
    }

    /**
     * Display the specified subscriber information.
     *
     * GET /api/v1/lists/{list_id}/subscribers/{id}
     *
     * @param  string  $list_id    List's id
     * @param  string  $id         Subsciber's id
     * @return \Illuminate\Http\Response
     */
    public function show($list_id, $id)
    {
        $list = \Mailixa\Model\MailList::findByUid($list_id);
        $item = \Mailixa\Model\Subscriber::
            where("uid", "=", $id)
            ->first();
        
        // check if item exists
        if (!is_object($item)) {
            return \Response::json(array("message"=>"Item not found"), 404);
        }
        
        // subscriber
        $subscriber = [
            'uid' => $item->uid,
            'email' => $item->email,
        ];
        
        foreach ($list->fields as $field) {
            if($field->tag != 'EMAIL') {
                $subscriber[$field->tag] = $item->getValueByField($field);
            }
        }
        
        return \Response::json(['subscriber' => $subscriber], 200);
    }
    
    /**
     * Subscribe a subscriber.
     *
     * GET /api/v1/lists/{list_id}/subscribers/{id}/subscribe
     *
     * @param  string  $list_id    List's id
     * @param  string  $id         Subsciber's id
     * @return \Illuminate\Http\Response
     */
    public function subscribe($list_id, $id)
    {        
        $subscriber = \Mailixa\Model\Subscriber::findByUid($id);
        
        // check if item exists
        if (!is_object($subscriber)) {
            return \Response::json(array("message"=>"Item not found"), 404);
        }
        
        // authorize
        if (\Gate::denies('subscribe', $subscriber)) {
            return \Response::json(array("message"=>"Unauthorized"), 401);
        }
        
        // Unsubscribe
        $subscriber->status = "subscribed";
        $subscriber->save();
        
        return \Response::json(array("message"=>"Subscribed"), 200);
    }
    
    /**
     * Unsubscribe a subscriber.
     *
     * GET /api/v1/lists/{list_id}/subscribers/{id}/unsubscribe
     *
     * @param  string  $list_id    List's id
     * @param  string  $id         Subsciber's id
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe($list_id, $id)
    {        
        $subscriber = \Mailixa\Model\Subscriber::findByUid($id);
        
        // check if item exists
        if (!is_object($subscriber)) {
            return \Response::json(array("message"=>"Item not found"), 404);
        }
        
        // authorize
        if (\Gate::denies('unsubscribe', $subscriber)) {
            return \Response::json(array("message"=>"Unauthorized"), 401);
        }
        
        // Unsubscribe
        $subscriber->status = "unsubscribed";
        $subscriber->save();
        
        return \Response::json(array("message"=>"Unsubscribed"), 200);
    }
    
    /**
     * Delete a subscriber.
     *
     * GET /api/v1/lists/{list_id}/subscribers/{id}/delete
     * 
     * @param  string  $list_id    List's id
     * @param  string  $id         Subsciber's id
     * @return \Illuminate\Http\Response
     */
    public function delete($list_id, $id)
    {        
        $subscriber = \Mailixa\Model\Subscriber::findByUid($id);
        
        // check if item exists
        if (!is_object($subscriber)) {
            return \Response::json(array("message"=>"Item not found"), 404);
        }
        
        // authorize
        if (\Gate::denies('delete', $subscriber)) {
            return \Response::json(array("message"=>"Unauthorized"), 401);
        }
        
        // Unsubscribe
        $subscriber->delete();
        
        return \Response::json(array("message"=>"Deleted"), 200);
    }
}
