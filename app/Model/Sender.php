<?php

/**
 * Sender class.
 *
 * Model class for countries
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
use Illuminate\Support\Facades\Log as LaravelLog;

class Sender extends Model
{
    // Statuses
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $user = $request->user();
        $query = self::select('senders.*');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('senders.email', 'like', '%'.$keyword.'%')
                        ->orwhere('senders.name', 'like', '%'.$keyword.'%');
                });
            }
        }

        // Other filter
        if (!empty($request->customer_id)) {
            $query = $query->where('senders.customer_id', '=', $request->customer_id);
        }

        return $query;
    }

    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    public function sendingServer()
    {
        return $this->belongsTo('Acelle\Model\SendingServer');
    }

    public static function pending()
    {
        return self::where('status', self::STATUS_PENDING);
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = self::filter($request);

        if (!empty($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email'
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        $rules = array(
            'email' => 'required|email|unique:senders,email,'.$this->id.',id',
            'name' => 'required',
        );

        return $rules;
    }

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function editRules()
    {
        $rules = array(
            'name' => 'required',
        );

        return $rules;
    }

    /**
     * Set sender status to pending.
     *
     * @var void
     */
    public function setPending()
    {
        $this->status = self::STATUS_PENDING;
        $this->save();
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
            while (self::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
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
     * Check if sender is verified.
     *
     * @return object
     */
    public function isVerified()
    {
        return $this->status == self::STATUS_VERIFIED;
    }

    /**
     * Get domain name from email.
     *
     * @return string
     */
    public function getDomain()
    {
        return explode('@', $this->email)[1];
    }

    /**
     * Get domain name from email.
     *
     * @return string
     */
    public static function getAllVerified()
    {
        return self::where('status', '=', self::STATUS_VERIFIED);
    }

    /**
     * Verify sender.
     *
     * @return string
     */
    public function updateVerificationStatus()
    {
        // only work for server of allowVerifyingOwnEmailsRemotely() == true
        $server = $this->sendingServer;
        if (!is_null($server)) {
            $verified = $server->mapType()->verifyIdentity($this->email);
            $this->status = $verified ? self::STATUS_VERIFIED : self::STATUS_PENDING;
            $this->save();
            LaravelLog::info(sprintf('Verify sender %s done, status: %s', $this->email, $this->status));
        } else {
            // verify by clicking on an Acelle link
            // so nothing to do here
        }
    }

    public function toRFCEmailAddress()
    {
        return "{$this->name} <{$this->email}>";
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function verifyWith($server)
    {
        // Check if it is already verify
        $this->updateVerificationStatus();

        if ($this->isVerified()) {
            return;
        }

        if ($server->allowVerifyingOwnEmailsRemotely()) {
            // this sender is bound with a specific sending server
            // set $this->sending_server_id = $server->id the correct way
            $this->sendingServer()->associate($server); 
            $this->save();
        }

        $this->setPending();

        // just in case it is already verified
        $this->updateVerificationStatus();
        $server->mapType()->sendVerificationEmail($this->toRFCEmailAddress());
    }
}
