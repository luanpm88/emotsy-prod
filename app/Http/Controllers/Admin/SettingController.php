<?php

namespace Mailixa\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;
use Mailixa\Http\Controllers\Controller;

class SettingController extends Controller
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
     * Display and update all settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // \Mailixa\Model\Setting::updateAll();
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
                    if ($settings[$name]['cat'] == 'general' && $request->user()->getOption('backend', 'setting_general') == 'yes') {
                        \Mailixa\Model\Setting::set($name, $value);
                    }
                    if ($settings[$name]['cat'] == 'sending' && $request->user()->getOption('backend', 'setting_sending') == 'yes') {
                        \Mailixa\Model\Setting::set($name, $value);
                    }
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
    
    /**
     * Update all urls.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUrls(Request $request)
    {
        \Mailixa\Model\Setting::set("url_unsubscribe", action('CampaignController@unsubscribe', ["message_id" => trans("messages.MESSAGE_ID")]));
        \Mailixa\Model\Setting::set("url_open_track", action('CampaignController@open', ["message_id" => trans("messages.MESSAGE_ID")]));
        \Mailixa\Model\Setting::set("url_click_track", action('CampaignController@click', ["message_id" => trans("messages.MESSAGE_ID"), "url" => trans("messages.URL")]));
        \Mailixa\Model\Setting::set("url_delivery_handler", action('DeliveryController@notify'));
        
        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.setting.updated'));
        return redirect()->action('Admin\SettingController@index', ["tab" => "3"]);
    }
}
