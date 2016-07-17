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
        return \Response::json(\Mailixa\Model\MailList::getAll()
            ->select("uid", "name", "default_subject", "from_email", "from_name", "status", "created_at", "updated_at")
            ->get()
        , 200);
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
        $list = \Mailixa\Model\MailList::
            select("uid", "name", "default_subject", "from_email", "from_name", "status", "created_at", "updated_at")
            ->where("uid", "=", $id)
            ->first();
        
        // check if item exists
        if (!is_object($list)) {
            return \Response::json(array("message"=>"Request Ok"), 404);
        }
        
        return \Response::json($list, 200);
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
