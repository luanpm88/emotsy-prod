<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;

class UserDbDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userdb:dump {customer-id} {dumpfile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize user database. That is, run migration against the specified connection to set up the database schema';

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
        $customerId = $this->argument('customer-id');
        $dumpfile = $this->argument('dumpfile');

        $dirname = dirname($dumpfile);

        if (!file_exists($dirname)) {
            throw new \Exception("Directory '$dirname' does not exist!");
        }

        echo "Exporting...\n";

        $tables = implode(' ', array_map(function($table) {
            return \DB::getTablePrefix().$table; }
            , config('userdb_tables'))
        );

        $masterDb = 'mysql';
        $connectionInfo = config("database.connections.{$masterDb}");

        $fullCmd = "mysqldump {$connectionInfo['database']} {$tables} --where='customer_id={$customerId}' \
            -u{$connectionInfo['username']} \
            -h{$connectionInfo['host']} \
            -P{$connectionInfo['port']} \
            -p{$connectionInfo['password']} 2>&1 > {$dumpfile}";
        $result = exec($fullCmd, $output, $return);

        if ($return != 0) {
            throw new \Exception("Dump faield: {$result}");
        }

        echo "+ Done. File written to {$dumpfile}\n";

        return 0;
    }

    private function loadRawSql($filename)
    {
        return file_get_contents(database_path("userdb/{$filename}"));
    }
}
