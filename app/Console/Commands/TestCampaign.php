<?php

namespace Mailixa\Console\Commands;

use Illuminate\Console\Command;

class TestCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $mail = new \Zend\Mail\Storage\Imap(array(
            'host'     => 'mail.elarion.com',
            'user'     => 'nghipm@elarion.com',
            //'port'     => '993',
            'password' => '',
            'ssl'      => 'SSL'
        ));

        $count = $mail->count(); // @TODO only works for INBOX folder, use $mail->getFolders to interate through all folders
        //$mail->removeMessage(5401);
        for($i = $count - 1; $i >= 0; $i--) {
            echo $i . " | ";
            try {
                $message = $mail->getMessage($i);
            } catch(\Exception $ex) {
                echo "\n";
                continue;
            }

            echo $mail->getUniqueId($i) . " [" . $mail->getCurrentFolder() . "] ";

            if ($message->hasFlag(\Zend\Mail\Storage::FLAG_SEEN)) {
                echo "- ";
                //continue;
            }
            // mark recent/new mails
            if ($message->hasFlag(\Zend\Mail\Storage::FLAG_RECENT)) {
                echo '! ';
            } else {
                echo '  ';
            }
            echo $message->subject . "\n";
        }
    }
}
