<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;

class UserDbSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userdb:sync {customer_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize a customer from Master DB to a local DB';

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
        $customerId = $this->argument('customer_id');
        $customer = \Acelle\Model\Customer::find($customerId);

        if (!$customer->hasLocalDb()) {
            throw new \Exception("Customer does not have a local db");
        }

        echo "Sync customer #{$customer->id} to {$customer->db_connection}...\n";

        $customer->createLocalInstance();

        echo "+ Done\n";

        return 0;
    }

    private function loadRawSql($filename)
    {
        return file_get_contents(database_path("userdb/{$filename}"));
    }
}
