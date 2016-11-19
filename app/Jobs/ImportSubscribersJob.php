<?php

namespace Acelle\Jobs;

use Acelle\Model\SystemJob;

class ImportSubscribersJob extends ImportExportJob
{
    // @todo this should better be a constant
    protected $file;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailList, $user, $file)
    {
        // call parent's constructor
        parent::__construct($mailList, $user);
        
        // Upload csv
        $this->file = join_paths($this->path, 'data.csv');
        rename($file, $this->file);
    }
    
    /**
     * Get import file name.
     *
     * @return string file path
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get import file name.
     *
     * @return void
     */
    public function updateStatus(array $data)
    {
        $systemJobModel = $this->getSystemJob();
        $json = ($systemJobModel->data) ? json_decode($systemJobModel->data, true) : [];
        $systemJobModel->data = json_encode(array_merge($json, $data));
        $systemJobModel->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->mailList->import2($this->file, $this->user, $this);
    }
}
