<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Model\Language;
use Acelle\Model\Template;
use Acelle\Model\Setting;
use Acelle\Model\Job;
use Acelle\Model\FailedJob;
use Acelle\Model\JobMonitor;
use Acelle\Model\Plugin;
use Artisan;
use Illuminate\Support\Facades\Session;
use Cache;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Exception;
use Acelle\Library\UpgradeManager;
use Acelle\Model\Notification;
use Acelle\Helpers\LicenseHelper;

class SystemCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'System cleanup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('route:clear');

        Artisan::call('view:clear');

        // Write settings
        Setting::writeDefaultSettings();

        // Clean up job related
        Artisan::call('queue:clear');
        Artisan::call('queue:flush');
        if (redis_enabled()) {
            Artisan::call('queue:clear redis');
        }
        Artisan::call('queue:prune-failed');
        JobMonitor::query()->delete();
        Job::query()->delete();
        FailedJob::query()->delete();
        try {
            Schema::table('jobs', function (Blueprint $table) {
                $table->dropIndex(['queue']);
            });
        } catch (Exception $ex) {
            // Index already dropped
        }

        // @important: do not clear cache, it will clean up all statistics
        // Cache::flush();
        // Artisan::call('cache:clear'); // ==> it will clean up all statistics

        Language::dump();
        Template::resetDefaultTemplates();
        Template::resetPopupTemplates();
        Artisan::call('queue:prune-batches --hours=24 --unfinished=-1'); // clean up any pending job batches
        Artisan::call('queue:restart');

        // Reset GEODB
        if (Setting::get('geoip.sqlite.dbname') != 'storage/app/GeoLite2-City.mmdb') {
            Setting::set('geoip.sqlite.dbname', 'storage/app/GeoLite2-City.mmdb');
            Setting::set('geoip.sqlite.source_url', 'https://acellemail.s3.amazonaws.com/GeoLite2-City.mmdb');
            Setting::set('geoip.sqlite.source_hash', 'b95ecaff82017c4f52577196c41db946');
            Setting::set('geoip.enabled', 'no');
        }

        // New way to load plugins: initialize the plugin index file
        Plugin::resetPluginMasterFile();

        // Merge .env.example to .env
        $manager = new UpgradeManager();
        $manager->mergeEnv(base_path('.env.example'));
        return 0;
    }
}
