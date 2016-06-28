<?php

namespace Mailixa\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;
use Mailixa\Http\Controllers\Controller;

class UserController extends Controller
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
        if ($request->format == "json") {
            $data = \Mailixa\Web\User::datatable($request);
            return response()->json($data);
        } else {
            return view('admin.users.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = new \Mailixa\Web\User(['backend_access' => 'no']);
        $user->status = "active";
        $user->fill($request->old());
       
        return view('admin.users.create', [
            'timezone_options' => \Mailixa\Web\User::allTimeZones(),
            'user' => $user,
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
        // Get current user
        $current_user = $request->user();
        $user = new \Mailixa\Web\User();
        
        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, $user->new_rules);
            
            // Save current user info
            $user->fill($request->all());
            $user->status = $request->status;
            $user->group_id = $request->group_id;
            $user->backend_access = 'yes';
            
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            
            // create user
            $user->save();
            
            // Upload and save avatar
            if ($request->hasFile('avatar')) {
                if ($request->file('avatar')->isValid()) {
                    $user->avatar = $user->uploadAvatar($request->file('avatar'));
                }
            }
            
            // create user
            $user->save();
            
            $request->session()->flash('alert-success', 'User was successfully created!');
            return redirect()->action('Admin\UserController@edit', $user->id);
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
        $user = \Mailixa\Web\User::find($id);
        $user->fill($request->old());
       
        
        return view('admin.users.edit', [
            'timezone_options' => \Mailixa\Web\User::allTimeZones(),
            'user' => $user,
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
        // Get current user
        $current_user = $request->user();
        $user = \Mailixa\Web\User::find($id);
        
        // save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, $user->rules);
            
            // Save current user info
            $user->fill($request->all());
            $user->status = $request->status;
            $user->group_id = $request->group_id;
            
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            
            // Upload and save avatar
            if ($request->hasFile('avatar')) {
                if ($request->file('avatar')->isValid()) {
                    $user->avatar = $user->uploadAvatar($request->file('avatar'));
                }
            }
            
            // create user
            $user->save();
            
            $request->session()->flash('alert-success', 'User was successfully updated!');
            return redirect()->action('Admin\UserController@edit', $user->id);
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
        $user = \Mailixa\Web\User::find($request->id);
        $user->delete();
        
        echo('<p class="alert alert-success">User was successfully deleted!<p>');
    }
    
    public function select2(Request $request)
    {
        $result = [["id"=>"1", "text"=>"One"], ["id"=>"2", "text"=>"Two"]];
        
        return response()->json($result);
    }
    
    public function switch_user(Request $request)
    {
        $user = \Mailixa\Web\User::find($request->customer_id);
        $orig_id = $request->user()->id;
        \Auth::login($user);
        \Session::put('orig_user_id', $orig_id);
        
        return redirect()->action('HomeController@index');
    }
    
    /**
     * Enable item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $user = \Mailixa\Web\User::find($request->id);
        
        $user->status = 'active';
        $user->save();
        
        echo('<p class="alert alert-success">'.trans('messages.user.enabled').'<p>');
    }
    
    /**
     * Disable item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $user = \Mailixa\Web\User::find($request->id);
        
        $user->status = 'inactive';
        $user->save();
        
        echo('<p class="alert alert-success">'.trans('messages.user.disabled').'<p>');
    }
}
