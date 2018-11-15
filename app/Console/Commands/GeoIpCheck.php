<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as LaravelLog;
use App;
use Acelle\Model\Setting;
use Acelle\Library\Notification\GeoIp as GeoIpNotification;
use Exception;

class GeoIpCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geoip:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the current GeoIp service';

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
        LaravelLog::info('Checking GeoIp database');
        if (!Setting::isYes('geoip.enabled')) {
            GeoIpNotification::warning([
                'title' => 'GeoIp not ready',
                'message' => 'GeoIp database is being installed in the background...']);
            LaravelLog::info('Setting up GeoIP database');
            $geoip = App::make('Acelle\Library\Contracts\GeoIpInterface');
            try {
                $geoip->setup();
                GeoIpNotification::cleanupSimilarNotifications();
            } catch (Exception $ex) {
                LaravelLog::error('Installing GeoIp database failed');
                GeoIpNotification::warning([
                    'title' => 'GeoIp failed to install',
                    'message' => 'Cannot install GeoIp database. See laravel.log for details'
                ]);
                throw $ex;
            }
            
            Setting::setYes('geoip.enabled');
            LaravelLog::info('GeoIP database is successfully installed');
        } else {
            GeoIpNotification::cleanupSimilarNotifications();
        }
    }
}
