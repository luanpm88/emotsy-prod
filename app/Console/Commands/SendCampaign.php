<?php

namespace Mailixa\Console\Commands;

use Illuminate\Console\Command;
use Mailixa\Model\Campaign;
use Mailixa\Model\User;
use Mailixa\Model\SendingServer;
use Mailixa\Library\Log;
use Mailgun\Mailgun;
use DB;

class SendCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
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
        Log::configure(storage_path().'/logs/mail.log');

        //$u = User::first();
        //$u->overQuota(); 
        //DB::enableQueryLog();

        //$s = SendingServer::find(3);
        //echo $s->sendingQuota();

        //var_dump(Campaign::getPendingCampaigns());

        /**********
        $c = Campaign::first();
        for ($i = 1; $i <= 5; ++$i) { 
            $pid = pcntl_fork(); 

            if (!$pid) { 
                
                print "In child $i\n"; 
                try {
                    DB::reconnect('mysql');
                    Campaign::first();
                } catch(\Exception $ex) {
                    echo $ex->getMessage() . "\n";
                } finally {
                    echo "Final\n";
                }
                sleep(1); 
                exit($i); 
            } 
        } 

        while (pcntl_waitpid(0, $status) != -1) { 
            $status = pcntl_wexitstatus($status); 
            echo "Child $status completed\n"; 
        } 
        ***********/

        // Creating the pool of threads(stored as array)

        /*
        for ($i = 1; $i <= 2; ++$i) { 
            $pid = pcntl_fork(); 

            if (!$pid) { 
                sleep(1); 
                print "In child $i " . getmypid() . "\n";
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                Gaugau::$gaugau[] = $i * 10 + $i;
                sleep(1);
                print_r(Gaugau::$gaugau);
                if ($i != 33) {
                    $x = 1/0;
                }
                exit($i); 
            }
        }
        

        while (pcntl_waitpid(0, $status) != -1) { 
            $status = pcntl_wexitstatus ($status);
            echo "Child $status completed\n";
            
        }
        */

        /*
        //dd(DB::getQueryLog());
        
        $transport = \Swift_MailTransport::newInstance();

        // Create the Mailer using your created Transport
        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance();

        $message->setContentType("text/plain; charset=utf-8");
        $message->getHeaders()->addTextHeader('Feedback-Type', 'abuse');
        $message->setSubject('Test Abuse report');
        $message->setFrom('minhnghivn@gmail.com');
        $message->setTo('bounce@simulator.amazonses.com');
        $message->setEncoder(\Swift_Encoding::get8bitEncoding());
        
        //$message->setBody('Hi Nghị');
        $message->setBody(file_get_contents('/tmp/abuse'));

        // Actually send
        $sent = $mailer->send($message);

        var_dump($sent);
        */

        $message = \Swift_Message::newInstance();
        $message->setContentType('text/plain; charset=utf-8');
        $message->setSubject('Test me from Mailgun');
        $message->setFrom('any@gaugau.com');
        $message->setTo('minhnghivn@gmail.com');
        $message->setReplyTo('any@gaugau.com');
        $message->setEncoder(\Swift_Encoding::get8bitEncoding());
        $message->setBody('<b>Hello world</b>', 'text/html');

        $mg = new Mailgun('key-04dbf1c76df28775e4c3d9215d5ca548');
        $domain = 'sandboxe80f3790d6784dcea27cbf61831dc094.mailgun.org';

        $result = $mg->sendMessage(
            $domain, array(
                'to' => 'minhnghivn@gmail.com',
            ),
            $message->toString()
        );
        var_dump(get_class($result));
        var_dump($result->http_response_body->id);

        /*
        //Customise the email - self explanatory
        $mg->sendMessage($domain, array(
                'from'=>'nghi@sandboxe80f3790d6784dcea27cbf61831dc094.mailgun.org',
                'to'=> 'minhnghivn@gmail.com',
                'subject' => 'The PHP SDK is awesome!',
                'text' => 'It is so simple to send a message.'
            )
        );
        */
    }
}
