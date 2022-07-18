<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use function Acelle\Helpers\updateTranslationFile;

class MergeTranslationFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:merge {current} {update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge translation phrases from $new to $current (overwrite). This is used when we have a new translation and want to apply it to the current file';

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
     * @return int
     */
    public function handle()
    {
        $current = $this->argument('current');
        $update = $this->argument('update');
        updateTranslationFile($current, $update, $overwrite = true, $deleteTargetKeys = false, $sort = true);
    }
}
