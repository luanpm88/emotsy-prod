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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailList, $user, $file)
    {
        // call parent's constructor
        parent::__construct($mailList, $user);
        
        // Upload csv
        $filename = 'data.csv';
        $file->move($this->path, $filename);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Info from job
        $user = $this->user;
        $list = $this->mailList;
        $systemJob = $this->getSystemJob();
        
        $directory = $this->path;

        // Import to database
        $filename = 'data.csv';
        $content = \File::get($directory.$filename);
        $lines = preg_split('/\r\n|\r|\n/', $content); // explode("\n\r", $content);
        $total = count($lines) - 1;
        $success = 0;
        $error = 0;
        $lines_per_second = 1;
        $headers = explode(',', $lines[0]);
        $header_names = explode(',', $lines[0]);

        // update header tags
        foreach ($headers as $key => $tag) {
            $tag = trim(strtoupper(preg_replace('!\s+!', '_', preg_replace('![\'|\"|\\r|\\n]!', '', rtrim($tag)))));
            $headers[$key] = $tag;
            if ($tag == 'EMAIL') {
                $main_index = $key;
            }
        }

        // check valid file
        if (!in_array('EMAIL', $headers)) {
            $systemJob->data = json_encode([
                "mail_list_uid" => $list->uid,
                "user_id" => $user->id,
                "message" => "<span style='color:red'>".trans('messages.invalid_csv_file')."</span>",
                "total" => $total,
                "success" => $success,
                "error" => $error,
                "percent" => "0"
            ]);
            $systemJob->save();
            
            $systemJob->setFailed();            
            return;
        }

        $content_cache = '';
        $count = '0';
        foreach ($lines as $key => $line) {

            // authorize
            if ($user->cannot('create', new \Acelle\Model\Subscriber(['mail_list_id' => $list->id]))) {
                $systemJob->data = json_encode([
                    "mail_list_uid" => $list->uid,
                    "user_id" => $user->id,
                    "message" => "<span style='color:red'>".trans('messages.error_add_max_quota').'</span><br />'.$content_cache,
                    "total" => $total,
                    "success" => $success,
                    "error" => $error,
                    "percent" => $count
                ]);
                $systemJob->save();

                // Action Log
                $list->log('import_max_error', $user, ['count' => $count]);
                
                $error_detail = trans('messages.error_add_max_quota');
                $myfile = file_put_contents($directory.'detail.log', $error_detail.PHP_EOL , FILE_APPEND | LOCK_EX);
                
                $systemJob->setFailed();
                return;
            }

            if ($key > 0) {
                $parts = explode(',', $line);
                if(isset($parts[$main_index])) {
                    $email = strtolower(trim(preg_replace('!\s+!', '_', preg_replace('![\'|\"|\\r|\\n]!', '', rtrim($parts[$main_index])))));
                } else {
                    $email = "";
                }

                $valid = $list->checkExsitEmail($email);
                if ($valid) {
                    //// save subscribers
                    $subscriber = new \Acelle\Model\Subscriber();
                    $subscriber->mail_list_id = $list->id;
                    $subscriber->email = $email;
                    $subscriber->status = 'subscribed';
                    $subscriber->user_id = $list->user_id;
                    $subscriber->save();

                    foreach ($parts as $key => $value) {
                        $value = trim(preg_replace('!\s+!', '_', preg_replace('![\'|\"|\\r|\\n]!', '', rtrim($value))));
                        if(isset($headers[$key])) {
                            $lf = $list->fields()->where('tag', '=', $headers[$key])->first();
                            if (is_object($lf)) {
                                //// save fields
                                $lfv = new \Acelle\Model\SubscriberField(array(
                                                'field_id' => $lf->id,
                                                'subscriber_id' => $subscriber->id,
                                                'value' => $value,
                                            ));
                                $lfv->save();
                            }
                        }
                    }

                    ++$success;
                    $error_detail = trans('messages.email_imported', ['time' => \Carbon\Carbon::now()->timezone($user->timezone)->format(trans('messages.datetime_format_2')), 'email' => $email]);                    
                } else {
                    ++$error;
                    $error_detail = trans('messages.email_existed_invalid', ['time' => \Carbon\Carbon::now()->timezone($user->timezone)->format(trans('messages.datetime_format_2')), 'email' => $email]);
                }
                if ($key % $lines_per_second == 0) {
                    $content_cache = trans('messages.import_export_statistics_line', [
                        'total' => $total,
                        'processed' => $success + $error,
                        'success' => $success,
                        'error' => $error,
                    ]);
                    $count = round((($success + $error) / $total) * 100, 0);
                    
                    // update system job
                    $systemJob->data = json_encode([
                        "mail_list_uid" => $list->uid,
                        "user_id" => $user->id,
                        "message" => $content_cache,
                        "total" => $total,
                        "success" => $success,
                        "error" => $error,
                        "percent" => $count
                    ]);
                    $systemJob->save();
                    
                    // Details
                    $myfile = file_put_contents($directory.'detail.log', $error_detail.PHP_EOL , FILE_APPEND | LOCK_EX);
                }
            }
        }

        $content_cache = trans('messages.import_export_statistics_line', [
            'total' => $total,
            'processed' => $success + $error,
            'success' => $success,
            'error' => $error,
        ]);
        
        // update system job
        $systemJob->data = json_encode([
            "mail_list_uid" => $list->uid,
            "user_id" => $user->id,
            "message" => $content_cache,
            "total" => $total,
            "success" => $success,
            "error" => $error,
            "percent" => 100
        ]);
        $systemJob->save();

        // Action Log
        $list->log('import_success', $user, ['count' => $success, 'error' => $error]);
        
        $systemJob->setDone();
    }
}
