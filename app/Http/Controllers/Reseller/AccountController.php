<?php

namespace Acelle\Http\Controllers\Reseller;

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
        $reseller = $request->user()->reseller;
        $reseller->getColorScheme();

        // Authorize
        if (\Gate::denies('profile', $reseller)) {
            return $this->notAuthorized();
        }

        // Save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, $reseller->rules());
            
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
                $request->session()->flash('alert-success', trans('messages.profile.updated'));
            }
        }
        
        if (!empty($request->old())) {
            $reseller->fill($request->old());
            // User info
            $reseller->user->fill($request->old());
        }
        
        return view('reseller.account.profile', [
            'reseller' => $reseller,
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
        $reseller = $request->user()->reseller;
        if (is_object($reseller->contact)) {
            $contact = $reseller->contact;
        } else {
            $contact = new \Acelle\Model\Contact([
                                        'first_name' => $reseller->first_name,
                                        'last_name' => $reseller->last_name,
                                        'email' => $reseller->user->email,
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
                    $reseller->contact_id = $contact->id;
                    $reseller->save();
                }
                $request->session()->flash('alert-success', 'Contact information was successfully updated!');
            }
        }

        return view('reseller.account.contact', [
            'reseller' => $reseller,
            'contact' => $contact->fill($request->old()),
        ]);
    }
}
