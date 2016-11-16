<?php

/**
 * MailList class.
 *
 * Model class for log mail list
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class MailList extends Model
{
    // Subscribers to import every time
    const IMPORT_BATCH_SIZE = 20000;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'default_subject', 'from_email', 'from_name', 'remind_message', 'send_to', 'email_daily', 'email_subscribe', 'email_unsubscribe', 'send_welcome_email', 'unsubscribe_notification',
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required',
        'from_email' => 'required|email',
        'from_name' => 'required',
        //'remind_message' => 'required',
        'contact.company' => 'required',
        'contact.address_1' => 'required',
        'contact.country_id' => 'required',
        'contact.state' => 'required',
        'contact.city' => 'required',
        'contact.zip' => 'required',
        'contact.phone' => 'required',
        'contact.email' => 'required|email',
        'contact.url' => 'url',
        'email_subscribe' => 'regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
        'email_unsubscribe' => 'regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
        'email_daily' => 'regex:"^[\W]*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4}[\W]*,{1}[\W]*)*([\w+\-.%]+@[\w\-.]+\.[A-Za-z]{2,4})[\W]*$"',
    );

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function fields()
    {
        return $this->hasMany('Acelle\Model\Field');
    }

    public function user()
    {
        return $this->belongsTo('Acelle\Model\User');
    }

    public function segments()
    {
        return $this->hasMany('Acelle\Model\Segment');
    }

    public function pages()
    {
        return $this->hasMany('Acelle\Model\Page');
    }

    public function page($layout)
    {
        return $this->pages()->where('layout_id', $layout->id)->first();
    }

    public function contact()
    {
        return $this->belongsTo('Acelle\Model\Contact');
    }

    public function subscribers()
    {
        return $this->hasMany('Acelle\Model\Subscriber');
    }

    public function campaigns()
    {
        return $this->hasMany('Acelle\Model\Campaign');
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (MailList::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;

            // Update custom order
            MailList::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
        });

        // Create uid when list created.
        static::created(function ($item) {
            //  Create list default fields
            $item->createDefaultFieds();
        });

        // detele
        static::deleted(function ($item) {
            //  Delete contact when list deleted
            $item->contact->delete();
            
            // Delete import jobs
            $item->importJobs()->delete();
            
            // Delete export jobs
            $item->exportJobs()->delete();
        });
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('*');
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

        // Keyword
        if (!empty(trim($request->keyword))) {
            $query = $query->where('name', 'like', '%'.$request->keyword.'%');
        }

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
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    /**
     * Get all fields.
     *
     * @return object
     */
    public function getFields()
    {
        return $this->fields()->orderBy('custom_order');
    }

    /**
     * Create default fields for list.
     */
    public function createDefaultFieds()
    {
        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.email'),
                            'tag' => 'EMAIL',
                            'required' => true,
                            'visible' => true,
                        ]);

        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.first_name'),
                            'tag' => \Acelle\Model\Field::formatTag(trans('messages.first_name_tag')),
                            'required' => false,
                            'visible' => true,
                        ]);

        $this->fields()->create([
                            'mail_list_id' => $this->id,
                            'type' => 'text',
                            'label' => trans('messages.last_name'),
                            'tag' => \Acelle\Model\Field::formatTag(trans('messages.last_name_tag')),
                            'required' => false,
                            'visible' => true,
                        ]);
    }

    /**
     * Get email field.
     *
     * @return object
     */
    public function getEmailField()
    {
        return $this->getFieldByTag('EMAIL');
    }

    /**
     * Get field by tag.
     *
     * @return object
     */
    public function getFieldByTag($tag)
    {
        return $this->fields()->where('tag', '=', $tag)->first();
    }

    /**
     * Get field by tag.
     *
     * @return object
     */
    public function getActiveSubscribers()
    {
        return $this->subscribers()->where('status', 'active')->get();
    }

    /**
     * Get field rules.
     *
     * @return object
     */
    public function getFieldRules()
    {
        $rules = [];
        foreach ($this->getFields as $field) {
            if ($field->tag == 'EMAIL') {
                $rules[$field->tag] = 'required|email';
            } elseif ($field->required) {
                $rules[$field->tag] = 'required';
            }
        }

        return $rules;
    }

    /**
     * Check if a email is exsit.
     *
     * @param string the email
     *
     * @return bool
     */
    public function checkExsitEmail($email)
    {
        $valid = !filter_var($email, FILTER_VALIDATE_EMAIL) === false &&
            !empty($email) &&
            $this->subscribers()->where('email', '=', $email)->count() == 0;

        return $valid;
    }

    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions($user = null)
    {
        $query = self::getAll();
        if (is_object($user)) {
            $query = $query->where('user_id', '=', $user->id);
        }
        $options = $query->orderBy('name')->get()->map(function ($item) {
            return ['value' => $item->uid, 'text' => $item->name.' ('.$item->subscribers()->count().' '.strtolower(trans('messages.subscribers')).')'];
        });

        return $options;
    }

    /**
     * Get segments select options.
     *
     * @return array
     */
    public function getSegmentSelectOptions()
    {
        $options = $this->segments->map(function ($item) {
            return ['value' => $item->uid, 'text' => $item->name.' ('.$item->subscribers()->count().' '.strtolower(trans('messages.subscribers')).')'];
        });

        return $options;
    }

    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function unsubscribeCount()
    {
        return $this->subscribers()->where('status', '=', 'unsubscribed')->count();
    }

    /**
     * Unsubscribe rate.
     *
     * @return array
     */
    public function unsubscribeRate()
    {
        if ($this->subscribers()->count() == 0) {
            return '#';
        }

        return round(($this->unsubscribeCount() / $this->subscribers()->count()) * 100, 1);
    }

    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function subscribeCount()
    {
        return $this->subscribers()->where('status', '=', 'subscribed')->count();
    }

    /**
     * Unsubscribe rate.
     *
     * @return array
     */
    public function subscribeRate()
    {
        if ($this->subscribers()->count() == 0) {
            return '#';
        }

        return round(($this->subscribeCount() / $this->subscribers()->count()) * 100, 1);
    }

    /**
     * Count unsubscribe.
     *
     * @return array
     */
    public function unconfirmedCount()
    {
        return $this->subscribers()->where('status', '=', 'unconfirmed')->count();
    }
    
    /**
     * Count blacklisted.
     *
     * @return array
     */
    public function blacklistedCount()
    {
        return $this->subscribers()->where('status', '=', 'blacklisted')->count();
    }
    
    /**
     * Count blacklisted.
     *
     * @return array
     */
    public function spamReportedCount()
    {
        return $this->subscribers()->where('status', '=', 'spam-reported')->count();
    }
    
    /**
     * Count by status.
     *
     * @return array
     */
    public static function subscribersCountByStatus($status, $user=null)
    {
        $query = \Acelle\Model\Subscriber::where('subscribers.status', '=', $status);
        
        if(isset($user) && $user->getOption('backend', 'user_read') != 'all') {
            $query = $query->join('mail_lists', 'mail_lists.id', '=', 'subscribers.mail_list_id')
                            ->where("mail_lists.user_id", "=", $user->id);
        }
        
        return $query->count();
    }

    /**
     * Add customer action log.
     */
    public function log($name, $user, $add_datas = [])
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        $data = array_merge($data, $add_datas);

        Log::create([
            'user_id' => $user->id,
            'type' => 'list',
            'name' => $name,
            'data' => json_encode($data),
        ]);
    }

    /**
     * url count.
     */
    public function urlCount()
    {
        $query = CampaignLink::join('campaigns', 'campaigns.id', '=', 'campaign_links.campaign_id')
            ->where('campaigns.mail_list_id', '=', $this->id);

        return $query->count();
    }

    /**
     * Open count.
     */
    public function openCount()
    {
        $query = OpenLog::join('tracking_logs', 'tracking_logs.message_id', '=', 'open_logs.message_id')
            ->whereIn('tracking_logs.subscriber_id', function ($query) {
                $query->select('subscribers.id')
                    ->from('subscribers')
                    ->where('subscribers.mail_list_id', '=', $this->id);
            });

        return $query->count();
    }

    /**
     * Open count.
     */
    public function clickCount()
    {
        $query = ClickLog::join('tracking_logs', 'tracking_logs.message_id', '=', 'click_logs.message_id')
            ->whereIn('tracking_logs.subscriber_id', function ($query) {
                $query->select('subscribers.id')
                    ->from('subscribers')
                    ->where('subscribers.mail_list_id', '=', $this->id);
            });

        return $query->distinct('url')->count('url');
    }

    /**
     * Open count.
     */
    public function openUniqCount()
    {
        $query = OpenLog::join('tracking_logs', 'tracking_logs.message_id', '=', 'open_logs.message_id')
            ->whereIn('tracking_logs.subscriber_id', function ($query) {
                $query->select('subscribers.id')
                    ->from('subscribers')
                    ->where('subscribers.mail_list_id', '=', $this->id);
            });

        return $query->distinct('subscriber_id')->count('subscriber_id');
    }

    /**
     * Tracking count.
     */
    public function trackingCount()
    {
        $query = TrackingLog::whereIn('tracking_logs.subscriber_id', function ($query) {
            $query->select('subscribers.id')
                    ->from('subscribers')
                    ->where('subscribers.mail_list_id', '=', $this->id);
        });

        return $query->count();
    }

    /**
     * Count open rate.
     *
     * @return number
     */
    public function openRate()
    {
        if ($this->trackingCount() == 0) {
            return 0;
        }

        return round(($this->openCount() / $this->trackingCount()) * 100, 0);
    }

    /**
     * Count open uniq rate.
     *
     * @return number
     */
    public function openUniqRate()
    {
        if ($this->trackingCount() == 0) {
            return 0;
        }

        return round(($this->openUniqCount() / $this->trackingCount()) * 100, 0);
    }

    /**
     * Count click rate.
     *
     * @return number
     */
    public function clickRate()
    {
        if ($this->urlCount() == 0) {
            return 0;
        }

        return round(($this->clickCount() / $this->urlCount()) * 100, 0);
    }

    /**
     * Get other lists.
     *
     * @return number
     */
    public function otherLists()
    {
        return \Auth::user()->lists()->where('id', '!=', $this->id)->get();
    }

    /**
     * Get name with subscrbers count.
     *
     * @return number
     */
    public function longName()
    {
        return $this->name.' - '.$this->subscribers()->count().' '.trans('messages.'.\Acelle\Library\Tool::getPluralPrase('subscriber', $this->subscribers()->count())).'';
    }

    /**
     * Copy new list.
     */
    public function copy($name)
    {
        $copy = $this->replicate();
        $copy->name = $name;
        $copy->created_at = \Carbon\Carbon::now();
        $copy->updated_at = \Carbon\Carbon::now();
        $copy->custom_order = 0;
        $copy->save();

        // Contact
        if (is_object($this->contact)) {
            $new_contact = $this->contact->replicate();
            $new_contact->save();

            // update contact
            $copy->contact_id = $new_contact->id;
            $copy->save();
        }

        // Remove default fields
        $copy->fields()->delete();
        // Fields
        foreach ($this->fields as $field) {
            $new_field = $field->replicate();
            $new_field->mail_list_id = $copy->id;
            $new_field->save();
        }
    }
    
    /**
     * Get import jobs.
     *
     * @return number
     */
    public function importJobs()
    {
        return \Acelle\Model\SystemJob::where("name","=","Acelle\Jobs\ImportSubscribersJob")
            ->where("data","like", "%\"mail_list_uid\":\"" . $this->uid . "\"%");            
    }
    
    /**
     * Get last export job.
     *
     * @return number
     */
    public function getLastImportJob()
    {
        return $this->importJobs()
            ->orderBy("created_at","DESC")
            ->first();
    }
    
    /**
     * Get export jobs.
     *
     * @return number
     */
    public function exportJobs()
    {
        return \Acelle\Model\SystemJob::where("name","=","Acelle\Jobs\ExportSubscribersJob")
            ->where("data","like", "%\"mail_list_uid\":\"" . $this->uid . "\"%");            
    }
    
    /**
     * Get last export job.
     *
     * @return number
     */
    public function getLastExportJob()
    {
        return $this->exportJobs()
            ->orderBy("created_at","DESC")
            ->first();
    }
    
    /**
     * Import subscribers.
     *
     * @return void
     */
    public function import($user, $job)
    {
        // Get info
        $systemJob = $job->getSystemJob();        
        $directory = $job->getPath();
        $filename = $job->getFilename();
        $content = \File::get($directory.$filename);
        
        // Import to database        
        $lines = preg_split('/\r\n|\r|\n/', $content);
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
                "mail_list_uid" => $this->uid,
                "user_id" => $user->id,
                "status" => "failed",
                "message" => "<span style='color:red'>".trans('messages.invalid_csv_file')."</span>",
                "total" => $total,
                "success" => $success,
                "error" => $error,
                "percent" => "0"
            ]);
            $systemJob->save();
         
            return;
        }

        $content_cache = '';
        $count = '0';
        foreach ($lines as $key => $line) {

            // authorize
            if ($user->cannot('create', new \Acelle\Model\Subscriber(['mail_list_id' => $this->id]))) {
                $systemJob->data = json_encode([
                    "mail_list_uid" => $this->uid,
                    "user_id" => $user->id,
                    "status" => "failed",
                    "message" => "<span style='color:red'>".trans('messages.error_add_max_quota').'</span><br />'.$content_cache,
                    "total" => $total,
                    "success" => $success,
                    "error" => $error,
                    "percent" => $count
                ]);
                $systemJob->save();

                // Action Log
                $this->log('import_max_error', $user, ['count' => $count]);
                
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

                $valid = $this->checkExsitEmail($email);
                if ($valid) {
                    //// save subscribers
                    $subscriber = new \Acelle\Model\Subscriber();
                    $subscriber->mail_list_id = $this->id;
                    $subscriber->email = $email;
                    $subscriber->status = 'subscribed';
                    $subscriber->user_id = $this->user_id;
                    $subscriber->save();

                    foreach ($parts as $key => $value) {
                        $value = trim(preg_replace('!\s+!', '_', preg_replace('![\'|\"|\\r|\\n]!', '', rtrim($value))));
                        if(isset($headers[$key])) {
                            $lf = $this->fields()->where('tag', '=', $headers[$key])->first();
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
                        "mail_list_uid" => $this->uid,
                        "user_id" => $user->id,
                        "status" => "running",
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
            "mail_list_uid" => $this->uid,
            "user_id" => $user->id,
            "status" => "done",
            "message" => $content_cache,
            "total" => $total,
            "success" => $success,
            "error" => $error,
            "percent" => 100
        ]);
        $systemJob->save();

        // Action Log
        $this->log('import_success', $user, ['count' => $success, 'error' => $error]);
    }
    
    /**
     * Export subscribers.
     *
     * @return void
     */
    public static function export($list, $user, $job)
    {
        // Info from job
        $systemJob = $job->getSystemJob();        
        $directory = $job->getPath();
        
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
                foreach ($list->fields as $key2 => $field) {
                    $value = $item->getValueByField($field);
                    $cols[] = $value;
                }
                $data[] = implode(',', $cols);

                ++$success;
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
            $systemJob->data = json_encode([
                "mail_list_uid" => $list->uid,
                "user_id" => $user->id,
                "status" => "running",
                "message" => $content_cache,
                "total" => $total,
                "success" => $success,
                "error" => $error,
                "percent" => round((($success + $error) / $total) * 100, 0)
            ]);
            $systemJob->save();
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
            "status" => "done",
            "message" => $content_cache,
            "total" => $total,
            "success" => $success,
            "error" => $error,
            "percent" => 100
        ]);
        $systemJob->save();
        
        // Action Log
        $list->log('export_success', $user, ['count' => $success, 'error' => $error]);
    }

    /**
     * Read a CSV file, returning the meta information
     *
     * @param string file path
     * @return array [$headers, $availableFields, $lineCount, $results]
     */
    public function getRemainingAddSubscribersQuota()
    {
        $max = $this->user->getOption('frontend', 'subscriber_max');
        $maxPerList = $this->user->getOption('frontend', 'subscriber_per_list_max');
        
        $remainingForList = $maxPerList - $this->reload()->subscribers->count();
        $remaining = $max - $this->reload()->user->subscribers->count();

        /*
        echo "\n--------------------\n";
        var_dump($max);
        var_dump($maxPerList);
        var_dump($this->subscribers->count());
        var_dump($remainingForList);
        var_dump($remaining);
        echo "\n--------------------\n";
        */

        $result = ($remainingForList > $remaining) ? $remaining : $remainingForList;
        return ($result > 0) ? $result : 0;
    }

    /**
     * Read a CSV file, returning the meta information
     *
     * @param string file path
     * @return array [$headers, $availableFields, $lineCount, $results]
     */
    public function readCsv($file)
    {
        // Read CSV files
        $lineCount = line_count($file);
        $reader = \League\Csv\Reader::createFromPath($file);
        $headers = array_map('strtoupper', $reader->fetchOne());        
        $fields = collect($this->fields)->map(function($field) { return strtoupper($field->tag); })->toArray();
        
        // list's fields found in the input CSV
        $availableFields = array_intersect($headers, $fields);
        
        // split the entire list into smaller batches
        $results = $reader->fetchAssoc($headers);

        return [$headers, $availableFields, $lineCount, $results];
    }

    /**
     * Import subscriber from a CSV file
     *
     * @param string original value
     * @return string quoted value
     * @todo: use MySQL escape function to correctly escape string with astrophe
     */
    public function import2($file, $user)
    {
        try {
            // retrive information
            //$systemJob = $job->getSystemJob();        
            //$directory = $job->getPath();
            //$filename = $job->getFilename();
            
            // Init the status
            /*
            $systemJob->data = json_encode([
                "mail_list_uid" => $this->uid,
                "user_id" => $user->id,
                "status" => "running",
                "message" => "",
                "total" => $lineCount,
                "success" => 0,
                "error" => 0,
                "percent" => "0"
            ]);
            */

            // Read CSV files
            list($headers, $availableFields, $lineCount, $results) = $this->readCsv($file);
            
            $count = 0;
            each_batch($results, self::IMPORT_BATCH_SIZE, true, function($batch) use ($availableFields, $user, $count) {
                // If user cannot create other IMPORT_BATCH_SIZE subscribers
                //echo "Batch original size: ".sizeof($batch)." ---\n";
                //echo "Current list size: " . $this->subscribers()->count()."\n";
                //echo "Remaining allowed: " . $this->getRemainingAddSubscribersQuota() . "\n";

                if (!$user->can('addMoreSubscribers', [$this, self::IMPORT_BATCH_SIZE])) {
                    // If use cannot create ANY other subscribers
                    if (!$user->can('addMoreSubscribers', [$this, 1])) {
                        // Action Log
                        $this->log('import_max_error', $user, ['count' => $count]);
                        
                        //$error_detail = trans('messages.error_add_max_quota');
                        //$myfile = file_put_contents($directory.'detail.log', $error_detail.PHP_EOL , FILE_APPEND | LOCK_EX);
                        throw new \Exception(trans('messages.error_add_max_quota'));
                    } else {
                        $batch = array_slice($batch, 0, $this->getRemainingAddSubscribersQuota());
                        //echo "Reduced batch size: ".sizeof($batch) . "\n";
                    }
                }
                
                // processing for every batch, 
                // using transaction to only commit at the end of the batch execution
                DB::beginTransaction();
                
                // current datetime in MySQL format
                $now = "FROM_UNIXTIME(" . Carbon::now()->timestamp . ")";
                
                // create a temporary table containing the input subscribers
                $tmpTable = table('__tmp_subscribers');
                $tmpFields = implode(',', array_map(function($field) { return "$field VARCHAR(255)"; }, $availableFields));
                DB::statement("DROP TEMPORARY TABLE IF EXISTS {$tmpTable};
                               CREATE TEMPORARY TABLE {$tmpTable}({$tmpFields});");

                // insert subscriber fields from the batch to the temporary table
                // extract only fields whose name matches TAG NAME of MailList
                $data = collect($batch)->map(function($r) use ($availableFields) {
                    return array_intersect_key($r, array_flip($availableFields));
                })->toArray();
                DB::table('__tmp_subscribers')->insert($data);

                // Actually insert subscribers to the main table
                // Skip ones that already exist
                DB::statement("DELETE FROM {$tmpTable} WHERE email IN (SELECT email FROM " . table('subscribers') . ")");
                DB::statement("INSERT INTO " . table('subscribers') . "(uid, mail_list_id, user_id, email, status, created_at, updated_at) 
                               SELECT UUID_SHORT(), " . $this->id . ", " . $user->id . ", uniq.email, " . db_quote(Subscriber::STATUS_SUBSCRIBED) . ", {$now}, {$now}
                               FROM (SELECT DISTINCT email FROM {$tmpTable}) uniq");

                // Insert subscribers' custom fields to the fields table
                foreach($availableFields as $field) {
                    $sql = "INSERT INTO " . table('subscriber_fields') . "(subscriber_id, field_id, value, created_at, updated_at)
                    SELECT t.subscriber_id, f.id, t.{$field}, {$now}, {$now}
                    FROM (SELECT main.id AS subscriber_id, tmp.{$field} FROM " . table('subscribers') . " main JOIN {$tmpTable} tmp ON tmp.email = main.email) t
                    JOIN " . table('fields') . " f ON f.tag = '{$field}'";
                    DB::statement($sql);
                }

                // Actually write to the database
                DB::commit();
            });
            $count += 1;
        } catch (\Exception $e) {
            /*
            $json = ($systemJob->data) ? json_decode($systemJob->data) : [];
            $systemJob->data = json_encode(array_merge($json, [
                'status' => 'failed',
                'message' => "<span style='color:red'>".$e->getMessage()."</span>",
            ]));
            $systemJob->save();
            */
        }
    }
    
    /**
     * Reload mail list information
     *
     * @return object mail list model
     */
    public function reload() {
        return self::find($this->id);
    }
}
