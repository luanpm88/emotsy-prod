<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class AccountController extends Controller
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
     * Update user profile.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function profile(Request $request)
    {
        // Get current user
        $admin = $request->user()->admin;
        $admin->getColorScheme();

        // Authorize
        if (\Gate::denies('profile', $admin)) {
            return $this->notAuthorized();
        }

        // Save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, $admin->rules());
            
            // Update user account for admin
            $user = $admin->user;
            $user->email = $request->email;
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }            
            $user->save();
            
            // Save current user info
            $admin->fill($request->all());
            
            // Upload and save image
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $admin->removeImage();
                    $admin->image = $admin->uploadImage($request->file('image'));
                }
            }
            
            // Remove image
            if ($request->_remove_image == 'true') {
                $admin->removeImage();
                $admin->image = '';
            }

            if ($admin->save()) {
                $request->session()->flash('alert-success', trans('messages.profile.updated'));
            }
        }
        
        if (!empty($request->old())) {
            $admin->fill($request->old());
            // User info
            $admin->user->fill($request->old());
        }
        
        return view('admin.account.profile', [
            'admin' => $admin,
        ]);
    }

    /**
     * Update user contact information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function contact(Request $request)
    {        
        // Get current user
        $admin = $request->user()->admin;
        if (is_object($admin->contact)) {
            $contact = $admin->contact;
        } else {
            $contact = new \Acelle\Model\Contact([
                                        'first_name' => $admin->first_name,
                                        'last_name' => $admin->last_name,
                                        'email' => $admin->user->email,
                                    ]);
        }

        // Create new company if null
        if (!is_object($contact)) {
            $contact = new \Acelle\Model\Contact();
        }

        // save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\Contact::$rules);

            $contact->fill($request->all());

            // Save current user info
            if ($contact->save()) {
                if (!is_object($contact)) {
                    $admin->contact_id = $contact->id;
                    $admin->save();
                }
                $request->session()->flash('alert-success', trans('messages.customer_contact.updated'));
            }
        }

        return view('admin.account.contact', [
            'admin' => $admin,
            'contact' => $contact->fill($request->old()),
        ]);
    }
}
