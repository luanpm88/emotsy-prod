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
        
        if (null !== $request->old()) {
            foreach ($request->old() as $name => $value) {
                if(isset($settings[$name])) {
                    $settings[$name]['value'] = $value;
                }
            }
        }
        
        // validate and save posted data
        if ($request->isMethod('post'))
        {
            $this->validate($request, \Mailixa\Model\Setting::rules());
            
            // Save settings
            foreach ($request->all() as $name => $value) {
                if ($name != "_token") {
                    \Mailixa\Model\Setting::set($name, $value);
                }
            }
            
            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.setting.updated'));
            return redirect()->action('Admin\SettingController@index');
        }
        
        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }
}
