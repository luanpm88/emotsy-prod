<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\RouletteWheel;
use Mailixa\Library\Log;

class SendingServer extends Model
{
    const DELIVERY_STATUS_SENT = 'sent';
    const DELIVERY_STATUS_FAILED = 'failed';

    public static $serverMapping = array(
        'amazon-api' => 'SendingServerAmazonApi',
        'amazon-smtp' => 'SendingServerAmazonSmtp',
        'smtp' => 'SendingServerSmtp',
        'sendmail' => 'SendingServerSendmail',
        'php-mail' => 'SendingServerPhpMail',
        'mailgun-api' => 'SendingServerMailgunApi',
        'mailgun-smtp' => 'SendingServerMailgunSmtp',
    );

    public static $serverPools = array();

    public static function resetServerPools()
    {
        self::$serverPools = array();
    }

    public function addMtaHeaders($message)
    {
        $message->getHeaders()->addTextHeader('X-Mailspeed-Sending-Server-Id', $this->uid);

        return $message;
    }

    /**
     * Get the segment of the campaign.
     */
    public function bounceHandler()
    {
        return $this->belongsTo('Mailixa\Model\BounceHandler');
    }

    public static function pickServer($campaign)
    {
        $servers = self::select('sending_servers.*', 'user_group_sending_servers.fitness')->join('user_group_sending_servers', 'user_group_sending_servers.sending_server_id', '=', 'sending_servers.id')->where('user_group_id', $campaign->user->user_group_id)->get();

        if (sizeof($servers) == 0) {
            throw new \Exception('No delivery servers available');
        }

        $serverSelection = array();
        foreach ($servers as $server) {
            $serverSelection[$server->id] = (float) $server->fitness;
        }

        $id = (int) RouletteWheel::generate($serverSelection);
        $selectedServer = self::find($id);

        if (!empty(self::$serverPools[$id])) {
            Log::info('Reuse sending server `'.self::$serverPools[$id]->name.'` (ID: '.$id.')');

            return self::$serverPools[$id];
        } else {
            Log::info('Initialize delivery server `'.$selectedServer->name.'` (ID: '.$id.')');

            return self::$serverPools[$id] = self::mapServerType($selectedServer);
        }
    }

    public static function mapServerType($server)
    {
        $class_name = '\Mailixa\Model\\'.self::$serverMapping[$server->type];

        return $class_name::find($server->id);
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public function getVerp($recipient)
    {
        if (empty($this->bounceHandler)) {
            return $recipient;
        } else {
            return str_replace('@', '+'.str_replace('@', '=', $recipient).'@', $this->bounceHandler->username);
        }
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
     * Associations.
     *
     * @var object | collect
     */
    public function user()
    {
        return $this->belongsTo('Mailixa\Model\User');
    }

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('sending_servers.*');

        if ($request->user()->getOption('backend', 'sending_server_read') == 'own') {
            $query = $query->where('sending_servers.user_id', '=', $request->user()->id);
        }

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('sending_servers.name', 'like', '%'.$keyword.'%')
                        ->orWhere('sending_servers.type', 'like', '%'.$keyword.'%')
                        ->orWhere('sending_servers.host', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query = $query->where('sending_servers.type', '=', $filters['type']);
            }
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
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

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
            while (SendingServer::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;

            // SendingServer custom order
            SendingServer::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'host', 'aws_access_key_id', 'aws_secret_access_key', 'aws_region', 'smtp_username',
        'smtp_password', 'smtp_port', 'smtp_protocol', 'quota_value', 'quota_base', 'quota_unit',
        'bounce_handler_id', 'feedback_loop_handler_id',
    ];

    /**
     * Type of server.
     *
     * @return object
     */
    public static function types()
    {
        return [
            'amazon-smtp' => [
                'cols' => [
                    'name' => 'required',
                    'host' => 'required',
                    'aws_access_key_id' => 'required',
                    'aws_secret_access_key' => 'required',
                    'aws_region' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => 'required',
                ],
            ],
            'amazon-api' => [
                'cols' => [
                    'name' => 'required',
                    'host' => 'required',
                    'aws_access_key_id' => 'required',
                    'aws_secret_access_key' => 'required',
                    'aws_region' => 'required',
                ],
            ],
            'smtp' => [
                'cols' => [
                    'name' => 'required',
                    'host' => 'required',
                    'smtp_username' => 'required',
                    'smtp_password' => 'required',
                    'smtp_port' => 'required',
                    'smtp_protocol' => 'required',
                ],
            ],
            'sendmail' => [
                'cols' => [
                    'name' => 'required',
                    'sendmail_path' => 'required',
                ],
            ],
            'php-mail' => [
                'cols' => [
                    'name' => 'required',
                ],
            ],
        ];
    }

    /**
     * Get select options.
     *
     * @return array
     */
    public static function getSelectOptions()
    {
        $query = self::getAll();
        $options = $query->orderBy('name')->get()->map(function ($item) {
            return ['value' => $item->uid, 'text' => $item->name];
        });

        return $options;
    }

    /**
     * Get sending server's quota.
     *
     * @return string
     */
    public function maxQuota()
    {
        $quota = $this->quota_value;
        if ($quota == '-1') {
            return '∞';
        } else {
            return $quota;
        }
    }

    /**
     * Get sending server's sending quota.
     *
     * @return string
     */
    public function sendingQuota()
    {
        $time_value = $this->quota_base;
        $time_unit = $this->quota_unit;

        if ($time_value == '-1') {
            $begin = null;
        } else {
            if ($time_unit == 'year') {
                $begin = \Carbon\Carbon::now()->subYear($time_value);
            } elseif ($time_unit == 'month') {
                $begin = \Carbon\Carbon::now()->subMonth($time_value);
            } elseif ($time_unit == 'week') {
                $begin = \Carbon\Carbon::now()->subWeek($time_value);
            } elseif ($time_unit == 'day') {
                $begin = \Carbon\Carbon::now()->subDay($time_value);
            } elseif ($time_unit == 'hour') {
                $begin = \Carbon\Carbon::now()->subHour($time_value);
            } elseif ($time_unit == 'minute') {
                $begin = \Carbon\Carbon::now()->subMinute($time_value);
            }
        }

        $query = \Mailixa\Model\TrackingLog::leftJoin('sending_servers', 'sending_servers.id', '=', 'tracking_logs.sending_server_id');
        if (isset($begin)) {
            $query = $query->where('tracking_logs.created_at', '>=', $begin);
        }

        $count = $query->count();

        return $count;

        //$query = \Mailixa\Model\TrackingLog::where('sending_server_id', $this->id)
        //    ->whereRaw("created_at >= NOW() - INTERVAL {$this->quota_base} {$this->quota_unit}");
        //    
        //return $query->count();
    }

    /**
     * Get sending server's sending quota rate.
     *
     * @return string
     */
    public function sendingQuotaUsage()
    {
        if ($this->maxQuota() == '∞') {
            return '0';
        } elseif ($this->maxQuota() == '0' || $this->sendingQuota() >= $this->maxQuota()) {
            return '100';
        }

        return round((($this->sendingQuota() / $this->maxQuota()) * 100), 2);
    }

    /**
     * Get user's sending quota rate.
     *
     * @return string
     */
    public function displaySendingQuotaUsage()
    {
        if ($this->maxQuota() == '∞') {
            return trans('messages.unlimited');
        }

        return $this->sendingQuotaUsage().'%';
    }

    /**
     * Get rules.
     *
     * @return string
     */
    public static function rules($type)
    {
        $rules = self::types()[$type]['cols'];
        $rules['quota_value'] = 'required|numeric';
        $rules['quota_base'] = 'required|numeric';
        $rules['quota_unit'] = 'required';

        return $rules;
    }

    /**
     * Quota display.
     *
     * @return string
     */
    public function displayQuota()
    {
        return $this->quota_value.' / '.$this->quota_base.' '.trans('messages.'.\Mailixa\Library\Tool::getPluralPrase($this->quota_unit, $this->quota_base));
    }
}
