<?php

namespace Mailixa\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;
use Mailixa\Http\Controllers\Controller;

class UserGroupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = \Mailixa\Model\UserGroup::getAll();
        return view('admin.user_groups.index', [
            'groups' => $groups,
        ]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $groups = \Mailixa\Model\UserGroup::search($request)->paginate($request->per_page);
        
        return view('admin.user_groups._list', [
            'groups' => $groups,
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
        
        $group = new \Mailixa\Model\UserGroup([
                                'backend_access' => false,
                                'frontend_access' => true,
                            ]);
        $group->fill($request->old());

        // authorize
        if (\Gate::denies('create', $group)) {
            return $this->notAuthorized();
        }
        
        // For options
        if(isset($request->old()["options"])) {
            $group->options = json_encode($request->old()["options"]);
        }
        $options = $group->getOptions();
        
        // For sending servers
        $sending_server_ids = "";
        if(isset($request->old()["sending_server_ids"])) {
            $sending_server_ids = implode(",", $request->old()["sending_server_ids"]);
        }
       
        return view('admin.user_groups.create', [
            'group' => $group,
            'options' => $options,
            'sending_server_ids' => $sending_server_ids,
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
        $group = new \Mailixa\Model\UserGroup(array());
        
        // authorize
        if (\Gate::denies('create', $group)) {
            return $this->notAuthorized();
        }
        
        // validate and save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Mailixa\Model\UserGroup::rules());
            
            $group->fill($request->all());
            $group->options = json_encode($request->options);
            $group->save();

            // For sending servers
            if (isset($request->sending_server_ids)) {
                $group->updateSendingServers($request->sending_server_ids);
            }         
            
            $request->session()->flash('alert-success', trans('messages.user_group.created'));
            return redirect()->action('Admin\UserGroupController@index');
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
    public function edit(Request $request, $id)
    {
        // Generate info
        $user = $request->user();        
        $group = \Mailixa\Model\UserGroup::find($id);
        $group->fill($request->old());
        
        // authorize
        if (\Gate::denies('update', $group)) {
            return $this->notAuthorized();
        }
        
        // For options
        if(isset($request->old()["options"])) {
            $group->options = json_encode($request->old()["options"]);
        }
        $options = $group->getOptions();
        
        // For sending servers
        $sending_server_ids = $group->getSendingServerIds();
        if(isset($request->old()["sending_server_ids"])) {
            $sending_server_ids = implode(",", $request->old()["sending_server_ids"]);
        }
        
        return view('admin.user_groups.edit', [
            'group' => $group,
            'options' => $options,
            'sending_server_ids' => $sending_server_ids,
        ]);
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
        // Generate info
        $user = $request->user();
        
        $group = \Mailixa\Model\UserGroup::find($id);
        
        // authorize
        if (\Gate::denies('update', $group)) {
            return $this->notAuthorized();
        }
        
        // validate and save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, \Mailixa\Model\UserGroup::rules());
            
            $group->fill($request->all());
            $group->options = json_encode($request->options);
            $group->save();
            
            // For sending servers
            if (isset($request->sending_server_ids)) {
                $group->updateSendingServers($request->sending_server_ids);
            }
            
            
            $request->session()->flash('alert-success', trans('messages.user_group.updated'));
            return redirect()->action('Admin\UserGroupController@index');
        }
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
            $item = \Mailixa\Model\UserGroup::find($row[0]);
            
            // authorize
            if (\Gate::denies('sort', $item)) {
                return $this->notAuthorized();
            }

            $item->custom_order = $row[1];
            $item->save();
        }
        
        echo trans('messages.user_groups.custom_order.updated');
    }
}
