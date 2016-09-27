<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;

class PlanController extends Controller
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
        if (\Gate::denies('read', new \Acelle\Model\Plan())) {
            return $this->notAuthorized();
        }

        $plans = \Acelle\Model\Plan::getAll();

        return view('admin.plans.index', [
            'plans' => $plans,
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
        if (\Gate::denies('read', new \Acelle\Model\Plan())) {
            return $this->notAuthorized();
        }

        $plans = \Acelle\Model\Plan::search($request)->paginate($request->per_page);

        return view('admin.plans._list', [
            'plans' => $plans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $plan = new \Acelle\Model\Plan();
        $plan->status = 'active';

        // authorize
        if (\Gate::denies('create', $plan)) {
            return $this->notAuthorized();
        }
        
        if (!empty($request->old())) {
            $plan->fill($request->old());
        }

        return view('admin.plans.create', [
            'plan' => $plan,
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
        // Get current user
        $user = $request->user();
        $plan = new \Acelle\Model\Plan();

        // authorize
        if (\Gate::denies('create', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $rules = $plan->rules();

            $this->validate($request, $rules);

            // Save current user info
            $plan->admin_id = $user->admin->id;
            $plan->fill($request->all());      
            $plan->status = 'active';
            
            if ($plan->save()) {                
                $request->session()->flash('alert-success', trans('messages.plan.created'));
                return redirect()->action('Admin\PlanController@index');
            }
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
        $plan = \Acelle\Model\Plan::findByUid($id);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $plan->fill($request->old());
        }

        return view('admin.plans.edit', [
            'plan' => $plan,
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
        // Get current user
        $user = $request->user();
        $plan = \Acelle\Model\Plan::findByUid($id);

        // authorize
        if (\Gate::denies('update', $plan)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
             $rules = $plan->rules();

            $this->validate($request, $rules);

            // Save current user info
            $plan->fill($request->all());      

            if ($plan->save()) {
                $request->session()->flash('alert-success', trans('messages.plan.updated'));
                return redirect()->action('Admin\PlanController@index');
            }
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
     * Enable item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $items = \Acelle\Model\Plan::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->enable();
            }            
        }

        // Redirect to my lists page
        echo trans('messages.plans.enabled');
    }

    /**
     * Disable item.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $items = \Acelle\Model\Plan::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.plans.disabled');
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
        $items = \Acelle\Model\Plan::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::denies('delete', $item)) {
                return;
            }
        }

        foreach ($items->get() as $item) {
            $item->delete();
        }

        // Redirect to my lists page
        echo trans('messages.plans.deleted');
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
            $item = \Acelle\Model\Plan::findByUid($row[0]);

            // authorize
            if (\Gate::denies('update', $item)) {
                return $this->notAuthorized();
            }

            $item->custom_order = $row[1];
            $item->save();
        }

        echo trans('messages.lists.custom_order.updated');
    }
}
