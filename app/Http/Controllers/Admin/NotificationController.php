<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Acelle\Http\Requests;
use Acelle\Http\Controllers\Controller;
use Acelle\Model\Plan;
use Acelle\Model\Setting;
use Acelle\Model\SendingServer;
use Acelle\Model\Notification;

class NotificationController extends Controller
{

    /**
     * Notification index.
     */
    public function index(Request $request)
    {
        return view('admin.notifications.index');
    }

    /**
     * Notification listing.
     */
    public function listing(Request $request)
    {
        $notifications = Notification::search($request)->paginate($request->per_page);

        return view('admin.notifications.listing', [
            'notifications' => $notifications,
        ]);
    }
}