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
        
        // SMTP
        $env = [
            'MAIL_DRIVER' => config("mail.driver"),
            'MAIL_HOST' => config("mail.host"),
            'MAIL_PORT' => config("mail.port"),
            'MAIL_USERNAME' => config("mail.username"),
            'MAIL_PASSWORD' => config("mail.password"),
            'MAIL_ENCRYPTION' => config("mail.encryption"),
            'MAIL_FROM_EMAIL' => config("mail.from")["address"],
            'MAIL_FROM_NAME' => config("mail.from")["name"],
        ];
        $env_rules = [
            'env.MAIL_DRIVER' => 'required',
            'env.MAIL_HOST' => 'required',
            'env.MAIL_PORT' => 'required',
            'env.MAIL_USERNAME' => 'required',
            'env.MAIL_PASSWORD' => 'required',
            'env.MAIL_ENCRYPTION' => 'required|email',
            'env.MAIL_FROM_EMAIL' => 'required',
            'env.MAIL_FROM_NAME' => 'required',
        ];

        // validate and save posted data
        if ($request->isMethod('post')) {
            
            if($this->isDemoMode()) {
                return $this->notAuthorized();
            }
            
            $rules = \Acelle\Model\Setting::rules();
            
            if ($request->mail_driver == 'smtp') {
                $rules = array_merge($rules, $smtp_rules);
            }
            $this->validate($request, $rules);
            
            // Check SMTP connection
            $site_info = $request->all();
            if ($request->mail_driver == 'smtp') {
                $rules = [];
                try {
                    $transport = \Swift_SmtpTransport::newInstance($request->smtp_hostname, $request->smtp_port, $request->smtp_encryption);
                    $transport->setUsername($request->smtp_username);
                    $transport->setPassword($request->smtp_password);
                    $mailer = \Swift_Mailer::newInstance($transport);
                    $mailer->getTransport()->start();
                } catch (\Swift_TransportException $e) {
                    $rules['smtp_valid'] = 'required';
                } catch (Exception $e) {
                    $rules['smtp_valid'] = 'required';
                }
                $this->validate($request, $rules);
            } else {
                $site_info["smtp_from_name"] = $site_info["site_name"];
                $site_info["smtp_from_email"] = $site_info["email"];
            }

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
            'env_rules' => $env_rules,
            'env' => $env,
        ]);
    }

    /**
     * Update all urls.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUrls(Request $request)
    {
        \Acelle\Model\Setting::set('url_unsubscribe', action('CampaignController@unsubscribe', ['message_id' => 'MESSAGE_ID']));
        \Acelle\Model\Setting::set('url_open_track', action('CampaignController@open', ['message_id' => 'MESSAGE_ID']));
        \Acelle\Model\Setting::set('url_click_track', action('CampaignController@click', ['message_id' => 'MESSAGE_ID', 'url' => 'URL']));
        \Acelle\Model\Setting::set('url_delivery_handler', action('DeliveryController@notify', ['stype' => '']));
        \Acelle\Model\Setting::set('url_update_profile', action('PageController@profileUpdateForm', array(
            'list_uid' => 'LIST_UID',
            'uid' => 'SUBSCRIBER_UID',
            'code' => 'SECURE_CODE'))
        );

        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.setting.updated'));

        return redirect()->action('Admin\SettingController@index', ['tab' => 'system_urls']);
    }
    
    /**
     * View system logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function logs(Request $request)
    {
        $path = base_path("artisan");
        $lines = 300;
        
        $error_logs = "";
        $file = file($path);
        for ($i = max(0, count($file)-$lines); $i < count($file); $i++) {
          $error_logs .= $file[$i];
        }
        
        return view('admin.settings.logs', [
            'error_logs' => $error_logs,
        ]);
    }
    
    /**
     * View system logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function download_log(Request $request)
    {
        $path = storage_path("logs/" . $request->file);
        
        return response()->download($path);
    }
}
