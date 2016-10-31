<?php

namespace Acelle\Jobs;

use Acelle\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportSubscribersJob extends ImportExportJob implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $systemJob = $this->getSystemJob();
            for($i =0; $i < 50; $i += 1) {
                $systemJob->data = $i;
                $systemJob->save();
                sleep(1);
                echo "$i \n";
            }
            $this->setDone();
            
        } catch (\Exception $ex) {
            $this->setFailed();
        }
    }
}
