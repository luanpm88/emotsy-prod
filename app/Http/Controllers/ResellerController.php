<?php

namespace Acelle\Http\Controllers;

use Illuminate\Http\Request;

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
     * Render reseller image.
     */
    public function avatar(Request $request)
    {
        // Get current reseller
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
    
    /**
     * User uid for editor
     */
    public function showUid(Request $request)
    {
        $user = $request->user();
        echo $user->uid;
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
        $orig_user = \Acelle\Model\User::findByUid($id);
        
        \Auth::login($orig_user);
        
        return redirect()->action('Admin\UserController@index');
    }
}
