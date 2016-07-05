<?php

namespace Mailixa\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;
use Mailixa\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Display and update all settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $settings = \Mailixa\Model\Setting::getAll();
        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }
}
