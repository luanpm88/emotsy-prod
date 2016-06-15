<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class AccountController extends Controller
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
     * Update user profile.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    **/
    public function profile(Request $request)
    {
        // Get current user
        $user = $request->user();
        
        // Authorize
        if (\Gate::denies('update', $user)) {
            return $this->notAuthorized();
        }
        
        // Save posted data
        if ($request->isMethod('post')) {
            
            $this->validate($request, \Mailixa\Model\User::$rules);
            
            // Save current user info
            $user->fill($request->all());
            
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            
            // Upload and save image
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $user->removeImage();                    
                    $user->image = $user->uploadImage($request->file('image'));
                }
            }
            
            // Remove image
            if ($request->_remove_image == 'true') {
                $user->removeImage();
                $user->image = "";
            }
            
            if ($user->save()) {
                $request->session()->flash('alert-success', trans('messages.profile.updated'));
            }
        }
        
        return view('account.profile', [
            'user' => $user->fill($request->old()),
        ]);
    }
    
    /**
     * Update user contact information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
    **/
    public function contact(Request $request)
    {
        // Get current user
        $user = $request->user();
        $contact = $user->contact;
        
        // Create new company if null
        if(!is_object($contact)) {
            $contact = new \Mailixa\Model\Contact();
            $contact->user_id = $user->id;
        }
        
        // authorize
        if (\Gate::denies('update', $contact)) {
            return $this->notAuthorized();
        }
        
        // save posted data
        if ($request->isMethod('post'))
        {            
            $this->validate($request, \Mailixa\Model\Contact::$rules);
            
            $contact->fill($request->all());
            
            // Save current user info
            if($contact->save())
            {
                $request->session()->flash('alert-success', 'Contact information was successfully updated!');
            }
        }

        return view('account.contact', [
            'user' => $user,
            'contact' => $contact->fill($request->old()),
        ]);
    }
}
