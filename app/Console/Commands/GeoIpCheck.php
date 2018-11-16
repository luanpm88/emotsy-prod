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
        $geoip = App::make('Acelle\Library\Contracts\GeoIpInterface');
        
        if (!Setting::isYes('geoip.enabled')) { // or check $geoip->isValid()
            GeoIpNotification::warning([
                'title' => 'GeoIP not ready',
                'message' => 'GeoIP database is being installed in the background. Process started at '.date("M-d-Y H:i:s")]);
            LaravelLog::info('Setting up GeoIP database');
            
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
