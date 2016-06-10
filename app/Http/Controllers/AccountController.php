<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class AccountController extends Controller
{
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
            
            $this->validate($request, \Mailixa\User::$rules);
            
            // Save current user info
            $user->fill($request->all());
            
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
            
            if ($user->save()) {
                $request->session()->flash('alert-success', trans('messages.profile.updated'));
            }
        }
        
        return view('account.profile', [
            'timezone_options' => \Mailixa\Library\Tool::allTimeZones(),
            'user' => $user->fill($request->old()),
        ]);
    }
}
