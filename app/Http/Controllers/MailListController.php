<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class MailListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = \Mailixa\Model\MailList::getAll()->orderBy("custom_order")->paginate(\Mailixa\Model\MailList::$itemsPerPage);
        
        return view('lists.index', [
            'lists' => $lists
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Generate info
        $user = $request->user();
        $list = new \Mailixa\Model\MailList();
        $list->contact = new \Mailixa\Model\Contact($user->contact->toArray());
        $list->send_to = $user->contact->email;
        
        // authorize
        if (\Gate::denies('create', $list)) {
            return $this->no_more_item($request);
        }
        
        // Get old post values
        if(null !== $request->old()) {
            $list->fill($request->old());
        }
        if(isset($request->old()["contact"])) {
            $list->contact->fill($request->old()["contact"]);
        }
        
        return view('lists.create', [
            'list' => $list,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Generate info
        $user = $request->user();        
        $list = new \Mailixa\Model\MailList();
        
        // authorize
        if (\Gate::denies('create', $list)) {
            return $this->no_more_item($request);
        }
        
        // validate and save posted data
        if ($request->isMethod('post'))
        {
            $this->validate($request, \Mailixa\Model\MailList::$rules);
            
            // Save contact
            $contact = \Mailixa\Model\Contact::create($request->all()["contact"]);
            $list->fill($request->all());
            $list->user_id = $user->id;
            $list->contact_id = $contact->id;
            $list->save();
            
            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.list.created'));
            return redirect()->action('MailListController@index');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    
    /**
     * Custom sort items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $sort = json_decode($request->sort);
        foreach($sort as $row) {
            $list = \Mailixa\Model\MailList::find($row[0]);
            $list->custom_order = $row[1];
            $list->save();            
        }
        
        echo trans('messages.lists.custom_order.updated');
    }
}
