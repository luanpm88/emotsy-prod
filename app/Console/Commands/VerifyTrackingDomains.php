<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;

class VerifyTrackingDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking-domains:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify All Tracking Domains';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domains = \Acelle\Model\TrackingDomain::verified()->get();
        foreach($domains as $domain) {
            echo "$domain->name\n";
            $domain->verify();
        }
    }
}
