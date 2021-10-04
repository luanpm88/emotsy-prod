<?php

namespace Acelle\Http\Controllers\Admin;

use Acelle\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Acelle\Model\Language;
use Acelle\Model\Template;
use Acelle\Model\Setting;
use Acelle\Model\Job;
use Acelle\Model\FailedJob;
use Artisan;
use Illuminate\Support\Facades\Session;
use Cache;

class Upgrade extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finalize(Request $request)
    {
        Setting::writeDefaultSettings();
        Cache::flush();
        Language::dump();
        Template::resetDefaultTemplates();
        Job::query()->delete();
        FailedJob::query()->delete();
        Artisan::call('queue:restart');

        // Upgrade post-processing
        if ($request->session()->has('upgraded')) {
            $request->session()->forget('upgraded');
            Session::save();

            // Redirect with success message
            $request->session()->flash('alert-success', trans('messages.upgrade.alert.upgrade_success'));
            return redirect()->action('Admin\SettingController@upgrade');
        } else {
            echo '<html>
                <head>
                    <meta http-equiv="refresh" content="3;'.url('/').'" />
                    <title>Finalization</title>
                </head>
                <body>
                    Finalization done! redirecting...
                </body>
            </html>';
        }
    }

    public function migrate(Request $request)
    {
        Artisan::call('migrate', ['--force' => true]);
        sleep(3);
        if ($request->session()->has('upgraded')) {
            $nextPage = url('/admin/upgrade/finalize');
            echo "Finalization in progress...<meta http-equiv=\"refresh\" content=\"5;URL='".$nextPage."'\" />";
        } else {
            echo "Migration done! Redirecting...<meta http-equiv=\"refresh\" content=\"5;URL='".url('/')."'\" />";
        }
    }
}
