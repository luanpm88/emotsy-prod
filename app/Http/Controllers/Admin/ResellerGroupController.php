<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class ResellerGroupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\ResellerGroup())) {
            return $this->notAuthorized();
        }
        
        $groups = \Acelle\Model\ResellerGroup::getAll();

        return view('admin.reseller_groups.index', [
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
        // authorize
        if (\Gate::denies('read', new \Acelle\Model\ResellerGroup())) {
            return $this->notAuthorized();
        }
        
        $groups = \Acelle\Model\ResellerGroup::search($request)->paginate($request->per_page);

        return view('admin.reseller_groups._list', [
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

        $group = new \Acelle\Model\ResellerGroup([
                                'backend_access' => false,
                                'frontend_access' => true,
                            ]);
        $group->fill($request->old());
        
        // authorize
        if (\Gate::denies('create', $group)) {
            return $this->notAuthorized();
        }

        // For permissions
        if (isset($request->old()['permissions'])) {
            $group->permissions = json_encode($request->old()['permissions']);
        }
        $permissions = $group->getPermissions();

        return view('admin.reseller_groups.create', [
            'group' => $group,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Generate info
        $user = $request->user();
        $group = new \Acelle\Model\ResellerGroup(array());
        
        // authorize
        if (\Gate::denies('create', $group)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\ResellerGroup::rules());

            $group->fill($request->all());
            $group->permissions = json_encode($request->permissions);
            $group->admin_id = $user->admin->id;
            $group->save();

            $request->session()->flash('alert-success', trans('messages.reseller_group.created'));

            return redirect()->action('Admin\ResellerGroupController@index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        // Generate info
        $user = $request->user();
        $group = \Acelle\Model\ResellerGroup::find($id);
        $group->fill($request->old());

        // authorize
        if (\Gate::denies('update', $group)) {
            return $this->notAuthorized();
        }

        // For permissions
        if (isset($request->old()['permissions'])) {
            $group->permissions = json_encode($request->old()['permissions']);
        }
        $permissions = $group->getPermissions();

        return view('admin.reseller_groups.edit', [
            'group' => $group,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Generate info
        $user = $request->user();

        $group = \Acelle\Model\ResellerGroup::find($id);

        // authorize
        if (\Gate::denies('update', $group)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, \Acelle\Model\ResellerGroup::rules());

            $group->fill($request->all());
            $group->permissions = json_encode($request->permissions);
            $group->save();

            $request->session()->flash('alert-success', trans('messages.reseller_group.updated'));

            return redirect()->action('Admin\ResellerGroupController@index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    /**
     * Custom sort items.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $sort = json_decode($request->sort);
        foreach ($sort as $row) {
            $item = \Acelle\Model\ResellerGroup::find($row[0]);

            // authorize
            if (\Gate::denies('sort', $item)) {
                return $this->notAuthorized();
            }

            $item->custom_order = $row[1];
            $item->save();
        }

        echo trans('messages.reseller_groups.custom_order.updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $lists = \Acelle\Model\ResellerGroup::whereIn('id', explode(',', $request->ids));

        foreach ($lists->get() as $item) {
            // authorize
            if (\Gate::denies('delete', $item)) {
                return;
            }
        }

        foreach ($lists->get() as $item) {
            $item->delete();
        }

        // Redirect to my lists page
        echo trans('messages.reseller_groups.deleted');
    }
}
