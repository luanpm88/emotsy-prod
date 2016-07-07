<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('auth');
    }
    
    /**
     * Render user image.
     *
     * @return void
     */
    public function avatar(Request $request) {
        // Get current user        
        if($request->uid != "0")
        {
            $user = \Mailixa\Model\User::findByUid($request->uid);
        }
        else
        {
            $user = new \Mailixa\Model\User();
        }
        if(!empty($user->imagePath())) {
            $img = \Image::make($user->imagePath());
        } else {
            $img = \Image::make(public_path("assets/images/placeholder.jpg"));
        }
        
        return $img->response();
    }
}
