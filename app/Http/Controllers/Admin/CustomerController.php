<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class CustomerController extends Controller
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
        if (\Gate::denies('read', new \Acelle\Model\Customer())) {
            return $this->notAuthorized();
        }

        $customers = \Acelle\Model\Customer::getAll();

        return view('admin.customers.index', [
            'customers' => $customers,
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
        if (\Gate::denies('read', new \Acelle\Model\Customer())) {
            return $this->notAuthorized();
        }

        $customers = \Acelle\Model\Customer::search($request)->paginate($request->per_page);

        return view('admin.customers._list', [
            'customers' => $customers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = new \Acelle\Model\Customer();
        $customer->status = 'active';
        $customer->uid = '0';
        $customer->fill($request->old());

        // authorize
        if (\Gate::denies('create', $customer)) {
            return $this->notAuthorized();
        }

        return view('admin.customers.create', [
            'customer' => $customer,
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
        $customer = new \Acelle\Model\Customer();
        $contact = new \Acelle\Model\Contact();

        // authorize
        if (\Gate::denies('create', $customer)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $rules = $customer->rules();
            $rules = array_merge($rules, [
                'customer_group_id' => 'required',
            ]);
            $this->validate($request, $rules);
            
            // Create user account for customer
            $user = new \Acelle\Model\User();
            $user->email = $request->email;
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }            
            $user->save();

            // Save current user info
            $customer->user_id = $user->id;
            $customer->creator_id = $request->user()->id;
            $customer->fill($request->all());
            $customer->customer_group_id = $request->customer_group_id;            
            $customer->status = 'active';
            
            if ($customer->save()) {
                // Upload and save image
                if ($request->hasFile('image')) {
                    if ($request->file('image')->isValid()) {
                        // Remove old images
                        $customer->removeImage();
                        $customer->image = $customer->uploadImage($request->file('image'));
                        $customer->save();
                    }
                }

                // Remove image
                if ($request->_remove_image == 'true') {
                    $customer->removeImage();
                    $customer->image = '';
                }

                $request->session()->flash('alert-success', trans('messages.customer.created'));

                return redirect()->action('Admin\CustomerController@index');
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
        $customer = \Acelle\Model\Customer::findByUid($id);

        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        $customer->fill($request->old());

        return view('admin.customers.edit', [
            'customer' => $customer,
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
        $customer = \Acelle\Model\Customer::findByUid($id);

        // authorize
        if (\Gate::denies('update', $customer)) {
            return $this->notAuthorized();
        }

        // save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, $customer->rules());
            
            // Update user account for customer
            $user = $customer->user;
            $user->email = $request->email;
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }            
            $user->save();
            
            // Save current user info
            $customer->fill($request->all());
            
            // Change group
            $customer->customer_group_id = $request->customer_group_id;
            
            // Upload and save image
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $customer->removeImage();
                    $customer->image = $customer->uploadImage($request->file('image'));
                }
            }
            
            // Remove image
            if ($request->_remove_image == 'true') {
                $customer->removeImage();
                $customer->image = '';
            }

            if ($customer->save()) {
                $request->session()->flash('alert-success', trans('messages.customer.updated'));
                return redirect()->action('Admin\CustomerController@index');
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
        $user = \Acelle\Web\User::find($request->id);

        $user->status = 'active';
        $user->save();

        echo '<p class="alert alert-success">'.trans('messages.user.enabled').'<p>';
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
        $user = \Acelle\Web\User::find($request->id);

        $user->status = 'inactive';
        $user->save();

        echo '<p class="alert alert-success">'.trans('messages.user.disabled').'<p>';
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
        $items = \Acelle\Model\Customer::whereIn('uid', explode(',', $request->uids));

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
        echo trans('messages.customers.deleted');
    }

    /**
     * Switch user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function switch_user(Request $request)
    {
        $user = \Acelle\Model\Customer::findByUid($request->uid);

        // authorize
        if (\Gate::denies('switch_user', $user)) {
            return;
        }
        
        $orig_id = $request->user()->uid;
        \Auth::login($user);
        \Session::put('orig_user_id', $orig_id);

        return redirect()->action('HomeController@index');
    }

    /**
     * Log in back user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function loginBack(Request $request)
    {
        $id = \Session::pull('orig_user_id');
        $orig_user = \Acelle\Model\Customer::findByUid($id);
        
        \Auth::login($orig_user);
        
        return redirect()->action('Admin\UserController@index');
    }

    /**
     * Render user image.
     */
    public function avatar(Request $request)
    {
        // Get current user
        if ($request->uid != '0') {
            $user = \Acelle\Model\Customer::findByUid($request->uid);
        } else {
            $user = new \Acelle\Model\Customer();
        }
        if (!empty($user->imagePath())) {
            $img = \Image::make($user->imagePath());
        } else {
            $img = \Image::make(public_path('assets/images/placeholder.jpg'));
        }

        return $img->response();
    }
}
