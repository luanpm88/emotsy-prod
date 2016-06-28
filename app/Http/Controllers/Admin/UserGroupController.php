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
        
        $group = new \Mailixa\Model\UserGroup();
        $group->fill($request->old());
        
        // authorize
        if (\Gate::denies('create', $group)) {
            return $this->notAuthorized();
        }
        
        //// For options
        //$options = $this->get_options($group, $request);
        //
        //// For delivery server
        //$group->getDeliveryServerToCustomerGroups($request->old());
       
        return view('admin.user_groups.create', [
            'group' => $group,
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
            $this->validate($request, \Mailixa\Model\UserGroup::$rules);
            
            $group->fill($request->all());
            // $group->user_id = $user->id;
            $group->save();
            
            //// Save options
            //$options = $this->save_options($group, $request);
            //
            //// update delivery server to customer groups
            //$group->updateDeliveryServerToCustomerGroups($request->all());
            
            
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
        
        //// For options
        //$options = $this->get_options($group, $request);
        //
        //// For delivery server
        //if (!empty($request->old())) {
        //    $group->getDeliveryServerToCustomerGroups($request->old());
        //}
        
        return view('admin.user_groups.edit', [
            'group' => $group,
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
            $this->validate($request, \Mailixa\Model\UserGroup::$rules);
            
            $group->fill($request->all());
            $group->save();
            
            //// Save options
            //$options = $this->save_options($group, $request);
            //
            //// update delivery server to customer groups
            //$group->updateDeliveryServerToCustomerGroups($request->all());
            
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
    
    public function delete(Request $request)
    {
        $group = \Mailixa\Web\UserGroup::find($request->id);
        $group->delete();
        
        echo('<p class="alert alert-success">User group was successfully deleted!<p>');
    }
    
    public function copy(Request $request)
    {
        $group = \Mailixa\Web\UserGroup::find($request->id);
        
        $group = $group->copy();

        
        echo('<p class="alert alert-success">User group was successfully copied!<p>');
    }
    
    public function get_options($group, $request)
    {
        foreach (\Mailixa\Web\UserGroupOption::getByCategory($group->id, "system.customer_common") as $option) {
            $options["common"][explode(".", $option->code)[2]] = $option->value;
        }
        foreach (\Mailixa\Web\UserGroupOption::getByCategory($group->id, "system.customer_servers") as $option) {
            $options["server"][explode(".", $option->code)[2]] = $option->value;
        }
        foreach (\Mailixa\Web\UserGroupOption::getByCategory($group->id, "system.customer_tracking_domains") as $option) {
            $options["tracking_domain"][explode(".", $option->code)[2]] = $option->value;
        }
        foreach (\Mailixa\Web\UserGroupOption::getByCategory($group->id, "system.customer_sending_domains") as $option) {
            $options["sending_domain"][explode(".", $option->code)[2]] = $option->value;
        }
        foreach (\Mailixa\Web\UserGroupOption::getByCategory($group->id, "system.customer_lists") as $option) {
            $options["list"][explode(".", $option->code)[2]] = $option->value;
        }
        foreach (\Mailixa\Web\UserGroupOption::getByCategory($group->id, "system.customer_campaigns") as $option) {
            $options["campaign"][explode(".", $option->code)[2]] = $option->value;
        }
        foreach (\Mailixa\Web\UserGroupOption::getByCategory($group->id, "system.customer_quota_counters") as $option) {
            $options["quota_counter"][explode(".", $option->code)[2]] = $option->value;
        }
        foreach (\Mailixa\Web\UserGroupOption::getByCategory($group->id, "system.customer_sending") as $option) {
            $options["sending"][explode(".", $option->code)[2]] = $option->value;
        }
        
        if (!empty($request->old())) {
            $options["common"] = $request->old()["common"];
            $options["server"] = $request->old()["server"];
            $options["tracking_domain"] = $request->old()["tracking_domain"];
            $options["sending_domain"] = $request->old()["sending_domain"];
            $options["list"] = $request->old()["list"];
            $options["campaign"] = $request->old()["campaign"];
            $options["quota_counter"] = $request->old()["quota_counter"];
            $options["sending"] = $request->old()["sending"];
        }
        
        return $options;
    }
    
    public function save_options($group, $request)
    {
        foreach ($request->all()["common"] as $key => $value) {
            \Mailixa\Web\UserGroupOption::set($group->id, "system.customer_common", $key, $value);
        }
        foreach ($request->all()["server"] as $key => $value) {
            \Mailixa\Web\UserGroupOption::set($group->id, "system.customer_servers", $key, $value);
        }
        foreach ($request->all()["tracking_domain"] as $key => $value) {
            \Mailixa\Web\UserGroupOption::set($group->id, "system.customer_tracking_domains", $key, $value);
        }
        foreach ($request->all()["sending_domain"] as $key => $value) {
            \Mailixa\Web\UserGroupOption::set($group->id, "system.customer_sending_domains", $key, $value);
        }
        foreach ($request->all()["list"] as $key => $value) {
            \Mailixa\Web\UserGroupOption::set($group->id, "system.customer_lists", $key, $value);
        }
        foreach ($request->all()["campaign"] as $key => $value) {
            \Mailixa\Web\UserGroupOption::set($group->id, "system.customer_campaigns", $key, $value);
        }
        foreach ($request->all()["quota_counter"] as $key => $value) {
            \Mailixa\Web\UserGroupOption::set($group->id, "system.customer_quota_counters", $key, $value);
        }
        foreach ($request->all()["sending"] as $key => $value) {
            \Mailixa\Web\UserGroupOption::set($group->id, "system.customer_sending", $key, $value);
        }
        foreach (\Mailixa\Web\UserGroupOption::defaultOptions() as $key => $val) {
            $parts = explode(".", $key);
            $new_value = \Mailixa\Web\UserGroupOption::get($group->id, $parts[0].".".$parts[1], $parts[2]);
            \Mailixa\Web\UserGroupOption::set($group->id, $parts[0].".".$parts[1], $parts[2], $new_value);
        }
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
