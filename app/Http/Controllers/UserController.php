<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class UserController extends Controller
{
    /**
     * Render user image.
     *
     * @return void
     */
    public function avatar(Request $request) {
        // Get current user        
        if($request->id != "0")
        {
            $user = \Mailixa\Model\User::find($request->id);
        }
        else
        {
            $user = new User();
        }
        if(!empty($user->imagePath())) {
            $img = \Image::make($user->imagePath());
        } else {
            $img = \Image::make(public_path("assets/images/placeholder.jpg"));
        }
        
        return $img->response();
    }
}
