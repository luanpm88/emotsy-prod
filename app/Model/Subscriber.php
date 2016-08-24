<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    const STATUS_SUBSCRIBED = 'subscribed';
    const STATUS_UNSUBSCRIBED = 'unsubscribed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mail_list_id',
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $fields_rules = array(

    );

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function mailList()
    {
        return $this->belongsTo('Acelle\Model\MailList');
    }

    public function campaign()
    {
        return $this->belongsTo('Acelle\Model\Campaign');
    }

    public function subscriberFields()
    {
        return $this->hasMany('Acelle\Model\SubscriberField');
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
            while (Subscriber::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }

    /**
     * Get rules.
     *
     * @var array
     */
    public function getRules()
    {
        $rules = $this->mailList->getFieldRules();
        $item_id = isset($this->id) ? $this->id : 'NULL';
        $rules['EMAIL'] = $rules['EMAIL'].'|unique:subscribers,email,'.$item_id.',id,mail_list_id,'.$this->mailList->id;

        return $rules;
    }

    /**
     * Update fields from request.
     */
    public function updateFields($params)
    {
        foreach ($params as $tag => $value) {
            $field = $this->mailList->getFieldByTag(str_replace('[]', '', $tag));
            if (is_object($field)) {
                $fv = SubscriberField::where('subscriber_id', '=', $this->id)->where('field_id', '=', $field->id)->first();
                if (!is_object($fv)) {
                    $fv = new SubscriberField();
                    $fv->subscriber_id = $this->id;
                    $fv->field_id = $field->id;
                }
                if (is_array($value)) {
                    $fv->value = implode(',', $value);
                } else {
                    $fv->value = $value;
                }
                $fv->save();
            }
        }
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($query, $request)
    {
        $query = $query->leftJoin('subscriber_fields', 'subscribers.id', '=', 'subscriber_fields.subscriber_id')
                        ->leftJoin('mail_lists', 'subscribers.mail_list_id', '=', 'mail_lists.id');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('subscribers.email', 'like', '%'.$keyword.'%')
                        ->orWhere('subscriber_fields.value', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            if (!empty($filters['status'])) {
                $query = $query->where('subscribers.status', '=', $filters['status']);
            }
        }

        return $query;
    }

    /**
     * Get all languages.
     *
     * @return collect
     */
    public static function search($request, $user = null)
    {
        if (!isset($user)) {
            $user = $request->user();
        }
        $query = self::select('subscribers.*');
        $query = $query->where('mail_lists.user_id', '=', $user->id);

        $query = self::filter($query, $request);

        if (isset($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * Get field value by list field.
     *
     * @return value
     */
    public function getValueByField($field)
    {
        $fv = SubscriberField::where('subscriber_id', '=', $this->id)->where('field_id', '=', $field->id)->first();
        if (is_object($fv)) {
            return $fv->value;
        } else {
            return '';
        }
    }

    /**
     * Get field value by list field.
     *
     * @return value
     */
    public function getValueByTag($tag)
    {
        $fv = SubscriberField::leftJoin('fields', 'fields.id', '=', 'subscriber_fields.field_id')
            ->where('subscriber_id', '=', $this->id)->where('fields.tag', '=', $tag)->first();
        if (is_object($fv)) {
            return $fv->value;
        } else {
            return '';
        }
    }

    /**
     * Set field.
     *
     * @return value
     */
    public function setField($field, $value)
    {
        $fv = SubscriberField::where('subscriber_id', '=', $this->id)->where('field_id', '=', $field->id)->first();
        if (!is_object($fv)) {
            $fv = new SubscriberField();
            $fv->field_id = $field->id;
            $fv->subscriber_id = $this->id;
        }

        $fv->value = $value;
        $fv->save();
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

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
     * Import subscribers from file.
     *
     * @return object
     */
    public static function import($user, $list)
    {
        $directory = storage_path('import/');

        // Import to database
        $filename = $list->id.'-data.csv';
        $content = \File::get($directory.$filename);
        $lines = explode("\n", $content);
        $total = count($lines) - 1;
        $success = 0;
        $error = 0;
        $lines_per_second = 1;
        $headers = explode(',', $lines[0]);
        $header_names = explode(',', $lines[0]);

        // update header tags
        foreach ($headers as $key => $tag) {
            $tag = trim(strtoupper(preg_replace('!\s+!', '_', preg_replace('![\'|\"]!', '', $tag))));
            $headers[$key] = $tag;
            if ($tag == 'EMAIL') {
                $main_index = $key;
            }
        }

        // check valid file
        if (!in_array('EMAIL', $headers)) {
            $str = "error\n<span style='color:red'>".trans('messages.invalid_csv_file').'</span>';
            $str .= "\n0";
            $bytes_written = \File::put($directory.$mail_list->list_id.'-process.log', $str);

            return;
        }

        $content_cache = '';
        $count = '0';
        foreach ($lines as $key => $line) {

            // authorize
            if ($user->cannot('create', new \Acelle\Model\Subscriber(['mail_list_id' => $list->id]))) {
                $str = "error\n<span style='color:red'>".trans('messages.error_add_max_quota').'</span><br />'.$content_cache;
                $str .= "\n".$count;
                $bytes_written = \File::put($directory.$list->id.'-process.log', $str);

                // Action Log
                $list->log('import_max_error', $user, ['count' => $count]);

                return;
            }

            if ($key > 0) {
                $parts = explode(',', $line);
                $email = $parts[$main_index];

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

                    ++$success;
                } else {
                    ++$error;
                }
                if ($key % $lines_per_second == 0) {
                    $content_cache = trans('messages.import_export_statistics_line', [
                                            'total' => $total,
                                            'processed' => $success + $error,
                                            'success' => $success,
                                            'error' => $error,
                                        ]); //"Total of ".$total." subscribers; ".($success+$error)." have been processed; ".$success." successfully and ".$error." with errors.";
                    $count = round((($success + $error) / $total) * 100, 0);
                    $str = "processing\n".$content_cache;
                    $str .= "\n".$count.'';
                    $bytes_written = \File::put($directory.$list->id.'-process.log', $str);
                }
            }
        }

        $content_cache = trans('messages.import_export_statistics_line', [
                                            'total' => $total,
                                            'processed' => $success + $error,
                                            'success' => $success,
                                            'error' => $error,
                                        ]);
        $str = "finished\n".$content_cache."\n100";
        $bytes_written = \File::put($directory.$list->id.'-process.log', $str);

        // Action Log
        $list->log('import_success', $user, ['count' => $success, 'error' => $error]);
    }

    /**
     * Export subscribers to csv.
     *
     * @return object
     */
    public static function export($user, $list)
    {
        $directory = storage_path('export/');
        // Import to database
        $total = $list->subscribers()->count();
        $success = 0;
        $error = 0;
        $lines_per_second = 1;
        $data = [];
        $headers = [];
        foreach ($list->getFields as $key => $field) {
            $headers[] = $field->tag;
        }
        $headers = implode(',', $headers);

        foreach ($list->subscribers as $key => $item) {
            $cols = [];
            if (true) {
                if (true) {
                    foreach ($list->fields as $key => $field) {
                        $value = $item->getValueByField($field);
                        $cols[] = $value;
                    }
                    $data[] = implode(',', $cols);

                    ++$success;
                } else {
                    ++$error;
                }
                if ($key % $lines_per_second == 0) {
                    $content_cache = trans('messages.import_export_statistics_line', [
                                            'total' => $total,
                                            'processed' => $success + $error,
                                            'success' => $success,
                                            'error' => $error,
                                        ]);
                    $str = "processing\n".$content_cache;
                    $str .= "\n".round((($success + $error) / $total) * 100, 0).'';
                    $bytes_written = \File::put($directory.$list->id.'-process.log', $str);
                }
            }
        }

        $str = $headers."\n".implode("\n", $data);
        $bytes_written = \File::put($directory.$list->id.'-data.csv', $str);
        $content_cache = trans('messages.import_export_statistics_line', [
                                            'total' => $total,
                                            'processed' => $success + $error,
                                            'success' => $success,
                                            'error' => $error,
                                        ]);
        $str = "finished\n".$content_cache."\n100";
        $bytes_written = \File::put($directory.$list->id.'-process.log', $str);

        // Action Log
        $list->log('export_success', $user, ['count' => $success, 'error' => $error]);
    }

    /**
     * Get secure code for updating subscriber.
     *
     * @param string $action
     */
    public function getSecurityToken($action)
    {
        $string = $this->email + $action + env('APP_SECRET');

        return md5($string);
    }

    /**
     * Create customer action log.
     *
     * @param string $cat
     * @param User   $user
     * @param array  $add_datas
     */
    public function log($name, $user, $add_datas = [])
    {
        $data = [
                'id' => $this->id,
                'email' => $this->email,
                'list_id' => $this->mail_list_id,
                'list_name' => $this->mailList->name,
        ];

        $data = array_merge($data, $add_datas);

        \Acelle\Model\Log::create([
                                'user_id' => $user->id,
                                'type' => 'subscriber',
                                'name' => $name,
                                'data' => json_encode($data),
                            ]);
    }

    /**
     * Copy to list.
     *
     * @param MailList $list
     */
    public function copy($list, $type = 'keep')
    {
        // find exists
        $copy = $list->subscribers()->where('email', '=', $this->email)->first();

        if (!is_object($copy)) {
            $copy = $this->replicate();
            $copy->mail_list_id = $list->id;
            $copy->save();
        } else {
            // return if keep
            if ($type == 'keep') {
                return false;
            }
        }

        // update fields
        foreach ($this->subscriberFields as $item) {
            foreach ($copy->mailList->fields as $field) {
                if ($item->field->tag == $field->tag) {
                    $copy->setField($field, $item->value);
                }
            }
        }

        return $copy;
    }

    /**
     * Move to list.
     *
     * @param MailList $list
     */
    public function move($list, $type = 'keep')
    {
        $this->copy($list, $type);

        $this->delete();
    }
}
