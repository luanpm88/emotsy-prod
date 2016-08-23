<?php

namespace Acelle\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Acelle\Http\Controllers\Controller;

class SettingController extends Controller
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
     * Display and update all settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // \Acelle\Model\Setting::updateAll();
        $settings = \Acelle\Model\Setting::getAll();
        if (null !== $request->old()) {
            foreach ($request->old() as $name => $value) {
                if (isset($settings[$name])) {
                    $settings[$name]['value'] = $value;
                }
            }
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, \Acelle\Model\Setting::rules());

            // Save settings
            foreach ($request->all() as $name => $value) {
                if ($name != '_token') {
                    if ($settings[$name]['cat'] == 'general' && $request->user()->getOption('backend', 'setting_general') == 'yes') {
                        \Acelle\Model\Setting::set($name, $value);
                    }
                    if ($settings[$name]['cat'] == 'sending' && $request->user()->getOption('backend', 'setting_sending') == 'yes') {
                        \Acelle\Model\Setting::set($name, $value);
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
        \Acelle\Model\Setting::set('url_unsubscribe', action('CampaignController@unsubscribe', ['message_id' => trans('messages.MESSAGE_ID')]));
        \Acelle\Model\Setting::set('url_open_track', action('CampaignController@open', ['message_id' => trans('messages.MESSAGE_ID')]));
        \Acelle\Model\Setting::set('url_click_track', action('CampaignController@click', ['message_id' => trans('messages.MESSAGE_ID'), 'url' => trans('messages.URL')]));
        \Acelle\Model\Setting::set('url_delivery_handler', action('DeliveryController@notify', ['suid' => trans('messages.SUID')]));

        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.setting.updated'));

        return redirect()->action('Admin\SettingController@index', ['tab' => '3']);
    }
}
