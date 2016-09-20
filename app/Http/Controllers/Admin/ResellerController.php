<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class ResellerController extends Controller
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
        if (\Gate::denies('read', new \Acelle\Model\Reseller())) {
            return $this->notAuthorized();
        }

        $resellers = \Acelle\Model\Reseller::getAll();

        return view('admin.resellers.index', [
            'resellers' => $resellers,
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
        if (\Gate::denies('read', new \Acelle\Model\Reseller())) {
            return $this->notAuthorized();
        }

        $resellers = \Acelle\Model\Reseller::search($request)->paginate($request->per_page);

        return view('admin.resellers._list', [
            'resellers' => $resellers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $reseller = new \Acelle\Model\Reseller();
        $reseller->status = 'active';
        $reseller->uid = '0';
        
        if (!empty($request->old())) {
            $reseller->fill($request->old());
            $reseller->reseller_group_id = $request->old()["reseller_group_id"];
            // User info
            $reseller->user = new \Acelle\Model\User();
            $reseller->user->fill($request->old());
        }

        // authorize
        if (\Gate::denies('create', $reseller)) {
            return $this->notAuthorized();
        }

        return view('admin.resellers.create', [
            'reseller' => $reseller,
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
        $current_user = $request->user();
        $reseller = new \Acelle\Model\Reseller();
        $contact = new \Acelle\Model\Contact();

        // authorize
        if (\Gate::denies('create', $reseller)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $rules = $reseller->rules();
            $rules = array_merge($rules, [
                'reseller_group_id' => 'required',
            ]);
            $this->validate($request, $rules);
            
            // Create user account for reseller
            $user = new \Acelle\Model\User();
            $user->email = $request->email;
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }            
            $user->save();

            // Save current user info
            $reseller->user_id = $user->id;
            $reseller->admin_id = $request->user()->admin->id;
            $reseller->fill($request->all());
            $reseller->reseller_group_id = $request->reseller_group_id;            
            $reseller->status = 'active';
            
            if ($reseller->save()) {
                // Upload and save image
                if ($request->hasFile('image')) {
                    if ($request->file('image')->isValid()) {
                        // Remove old images
                        $reseller->removeImage();
                        $reseller->image = $reseller->uploadImage($request->file('image'));
                        $reseller->save();
                    }
                }

                // Remove image
                if ($request->_remove_image == 'true') {
                    $reseller->removeImage();
                    $reseller->image = '';
                }

                $request->session()->flash('alert-success', trans('messages.admin.created'));

                return redirect()->action('Admin\ResellerController@index');
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
        $reseller = \Acelle\Model\Reseller::findByUid($id);

        // authorize
        if (\Gate::denies('update', $reseller)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $reseller->fill($request->old());
            $reseller->reseller_group_id = $request->old()["reseller_group_id"];
            // User info
            $reseller->user = new \Acelle\Model\User();
            $reseller->user->fill($request->old());
        }

        return view('admin.resellers.edit', [
            'reseller' => $reseller,
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
        $current_user = $request->user();
        $reseller = \Acelle\Model\Reseller::findByUid($id);

        // authorize
        if (\Gate::denies('update', $reseller)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $rules = $reseller->rules();
            $rules = array_merge($rules, [
                'reseller_group_id' => 'required',
            ]);
            $this->validate($request, $rules);
            
            // Update user account for reseller
            $user = $reseller->user;
            $user->email = $request->email;
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }            
            $user->save();
            
            // Save current user info
            $reseller->fill($request->all());
            
            // Change group
            $reseller->reseller_group_id = $request->reseller_group_id;
            
            // Upload and save image
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $reseller->removeImage();
                    $reseller->image = $reseller->uploadImage($request->file('image'));
                }
            }
            
            // Remove image
            if ($request->_remove_image == 'true') {
                $reseller->removeImage();
                $reseller->image = '';
            }

            if ($reseller->save()) {
                $request->session()->flash('alert-success', trans('messages.admin.updated'));
                return redirect()->action('Admin\ResellerController@index');
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

    public function select2(Request $request)
    {
        $result = [['id' => '1', 'text' => 'One'], ['id' => '2', 'text' => 'Two']];

        return response()->json($result);
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
        $items = \Acelle\Model\Reseller::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->enable();
            }            
        }

        // Redirect to my lists page
        echo trans('messages.resellers.disabled');
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
        $items = \Acelle\Model\Reseller::whereIn('uid', explode(',', $request->uids));

        foreach ($items->get() as $item) {
            // authorize
            if (\Gate::allows('update', $item)) {
                $item->disable();
            }
        }

        // Redirect to my lists page
        echo trans('messages.resellers.disabled');
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
        $items = \Acelle\Model\Reseller::whereIn('uid', explode(',', $request->uids));

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
        echo trans('messages.resellers.deleted');
    }

    /**
     * Render user image.
     */
    public function avatar(Request $request)
    {
        // Get current user
        if ($request->uid != '0') {
            $reseller = \Acelle\Model\Reseller::findByUid($request->uid);
        } else {
            $reseller = new \Acelle\Model\Reseller();
        }
        if (!empty($reseller->imagePath())) {
            $img = \Image::make($reseller->imagePath());
        } else {
            $img = \Image::make(public_path('assets/images/placeholder.jpg'));
        }

        return $img->response();
    }
}
