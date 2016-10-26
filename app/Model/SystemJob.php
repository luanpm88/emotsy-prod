<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SystemJob extends Model
{
    use DispatchesJobs;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'item_id', 'percent', 'status'
    ];
    
    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 10;
    
    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo('Acelle\Model\User');
    }
    
    /**
     * Get list.
     *
     * Object
     */
    public function mailList()
    {
        return \Acelle\Model\MailList::find($this->item_id);
    }
    
    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // detele
        static::deleting(function ($item) {
            // delete log
            $uploaded_dir = storage_path('job/' . $item->id);
            if (file_exists($uploaded_dir)) {   
                \Acelle\Library\Tool::xdelete($uploaded_dir);                
            }            
        });
    }
    
    /**
     * Get the current job.
     */
    public static function getCurrent($type)
    {
        return \Acelle\Model\SystemJob::where("type", "=", $type)
            ->where("current", "=", true)
            ->first();
    }
    
    /**
     * Start a job.
     *
     * @var array
     */
    public function start()
    {
        // Update current
        \Acelle\Model\SystemJob::where("type", "=", $this->type)->update(['current' => false]);
        $this->current = true;
        $this->save();
        
        $system_job = $this;
        
        $job = (new \Acelle\Jobs\RunSystemJob($system_job));
        $this->dispatch($job);
        
        return true;
    }
    
    /**
     * Run a job.
     *
     * @var array
     */
    public function run()
    {        
        $system_job = $this;
        switch ($this->type) {
            case 'import-subscriber':
                $this->importSubscribers();
                break;
            case 'export-subscriber':
                $this->exportSubscribers();
                break;
        }
    }
    
    /**
     * Import subscribers.
     *
     * @var array
     */
    public function importSubscribers()
    {
        // Info from job
        $user = $this->user;
        $list = \Acelle\Model\MailList::find($this->item_id);
        
        $directory = storage_path('job/' . $this->id . "/");

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
            $this->status = "error";
            $this->message = "<span style='color:red'>".trans('messages.invalid_csv_file').'</span>';
            $this->save();
            
            return;
        }

        $content_cache = '';
        $count = '0';
        foreach ($lines as $key => $line) {

            // authorize
            if ($user->cannot('create', new \Acelle\Model\Subscriber(['mail_list_id' => $list->id]))) {
                $this->status = "error";
                $this->message = "<span style='color:red'>".trans('messages.error_add_max_quota').'</span><br />'.$content_cache;
                $this->percent = $count;
                $this->save();

                // Action Log
                $list->log('import_max_error', $user, ['count' => $count]);
                
                $error_detail = trans('messages.error_add_max_quota');
                $myfile = file_put_contents($directory.'detail.log', $error_detail.PHP_EOL , FILE_APPEND | LOCK_EX);

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
                    $this->status = "running";
                    $this->message = $content_cache;
                    $this->percent = $count;
                    $this->save();
                    
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
        $this->status = "finished";
        $this->message = $content_cache;
        $this->percent = 100;
        $this->save();

        // Action Log
        $list->log('import_success', $user, ['count' => $success, 'error' => $error]);
    }
    
    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::where('user_id', '=', $user->id);
        $query = $query->where('type', '=', $request->type);

        return $query;
    }
    
    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = self::filter($request);
        
        $query = $query->orderBy($request->sort_order, $request->sort_direction);

        return $query;
    }
    
    /**
     * Export subscribers to csv.
     *
     * @return object
     */
    public function exportSubscribers()
    {
        // Info from job
        $user = $this->user;
        $list = \Acelle\Model\MailList::find($this->item_id);
        
        $directory = storage_path('job/' . $this->id . "/");
        $file_path = $directory.'data.csv';
        // Import to database
        $total = $list->subscribers()->count();
        $success = 0;
        $error = 0;
        $lines_per_second = 1;        
        $headers = [];
        foreach ($list->getFields as $key => $field) {
            $headers[] = $field->tag;
        }
        $headers = implode(',', $headers);
        
        // write csv
        $myfile = file_put_contents($file_path, $headers.PHP_EOL , FILE_APPEND | LOCK_EX);
        
        $num = 100;
        for($page = 0; $page <= ceil($total/$num); $page++) { // ceil($total/$num)
            $data = [];
            foreach ($list->subscribers()->skip($page*$num)->take($num)->get() as $key => $item) {
                $cols = [];
                if (true) {
                    if (true) {
                        foreach ($list->fields as $key2 => $field) {
                            $value = $item->getValueByField($field);
                            $cols[] = $value;
                        }
                        $data[] = implode(',', $cols);
        
                        ++$success;
                    } else {
                        ++$error;
                    }
                }
            }
            
            // write csv
            $myfile = file_put_contents($file_path, implode("\r\n", $data).PHP_EOL , FILE_APPEND | LOCK_EX);
            
            $content_cache = trans('messages.import_export_statistics_line', [
                'total' => $total,
                'processed' => $success + $error,
                'success' => $success,
                'error' => $error,
            ]);
            
            // update system job
            $this->status = "running";
            $this->message = $content_cache;
            $this->percent = round((($success + $error) / $total) * 100, 0);
            $this->save();
        }
        
        $content_cache = trans('messages.import_export_statistics_line', [
            'total' => $total,
            'processed' => $success + $error,
            'success' => $success,
            'error' => $error,
        ]);
        
        // update system job
        $this->status = "finished";
        $this->message = $content_cache;
        $this->percent = 100;
        $this->save();
        
        // Action Log
        $list->log('export_success', $user, ['count' => $success, 'error' => $error]);
    }
    
    /**
     * Run time.
     *
     * @return collect
     */
    public function runTime()
    {
        return gmdate("H:i:s", -$this->updated_at->diffInSeconds($this->created_at, false));
    }
}
