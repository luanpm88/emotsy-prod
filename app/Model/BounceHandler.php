<?php

namespace Acelle\Model;

use Acelle\Library\Log;

class BounceHandler extends DeliveryHandler
{
    protected $table = 'bounce_handlers';

    public function processMessage($mbox, $msgNo)
    {
        try {
            $header = imap_headerinfo($mbox, $msgNo);
            $body = imap_body($mbox, $msgNo, FT_PEEK);

            //$bouncedAddress = $this->getBouncedAddress($header->toaddress);
            //print_r($bouncedAddress . "\n");
            //if (empty($bouncedAddress)) {
            //    throw new \Exception("not a bounce message");
            //}

            $msgId = $this->getMessageId($body);

            if (empty($msgId)) {
                imap_setflag_full($mbox, $msgNo, '\\Seen \\Flagged');
                throw new \Exception('cannot find Message-ID, skipped');
            }

            Log::info('Processing bounce notification for message '.$msgId);

            $trackingLog = TrackingLog::where('message_id', $msgId)->first();
            if (empty($trackingLog)) {
                throw new \Exception('not a bounce message, skipped');
            }

            // record a bounce log, one message may have more than one
            $bounceLog = new BounceLog();
            $bounceLog->message_id = $msgId;
            $bounceLog->bounce_type = 'unknown'; // @TODO fill in the NULL value here
            $bounceLog->raw = imap_fetchheader($mbox, $msgNo).PHP_EOL.$body;
            $bounceLog->save();

            // just delete the bounce notification email
            imap_delete($mbox, $msgNo);

            Log::info('Bounce recorded for message '.$msgId);
        } catch (\Exception $ex) {
            Log::warning($ex->getMessage());
        }
    }

    public function getBouncedAddress($to)
    {
        preg_match('/(?<=\+)[^\+]+=[^@]+(?=@)/', $to, $matched);
        if (sizeof($matched) == 0) {
            return;
        } else {
            return str_replace('=', '@', $matched[0]);
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
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('bounce_handlers.*');

        if ($request->user()->getOption('backend', 'bounce_handler_read') == 'own') {
            $query = $query->where('bounce_handlers.user_id', '=', $request->user()->id);
        }

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('bounce_handlers.name', 'like', '%'.$keyword.'%')
                        ->orWhere('bounce_handlers.type', 'like', '%'.$keyword.'%')
                        ->orWhere('bounce_handlers.host', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            if (!empty($filters['type'])) {
                $query = $query->where('bounce_handlers.type', '=', $filters['type']);
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
            while (BounceHandler::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;

            // BounceHandler custom order
            BounceHandler::getAll()->increment('custom_order', 1);
            $item->custom_order = 0;
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'host', 'port', 'username', 'password', 'protocol',
    ];

    /**
     * Get validation rules.
     *
     * @return object
     */
    public static function rules()
    {
        return [
            'name' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'protocol' => 'required',
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

        $options = $query->orderBy('name', 'asc')->get()->map(function ($item) {
            return ['value' => $item->id, 'text' => $item->name];
        });

        return $options;
    }
}
