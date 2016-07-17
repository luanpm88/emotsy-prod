<?php

namespace Mailixa\Http\Controllers\Api;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;
use Mailixa\Http\Controllers\Controller;

class MailListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = \Mailixa\Model\MailList::getAll()
            ->select("uid", "name", "default_subject", "from_email", "from_name", "status", "created_at", "updated_at")
            ->get();
        
        return \Response::json($lists, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = \Mailixa\Model\MailList::
            where("uid", "=", $id)
            ->first();
        
        // check if item exists
        if (!is_object($item)) {
            return \Response::json(array("message"=>"Item not found"), 404);
        }
        
        // statistics
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
