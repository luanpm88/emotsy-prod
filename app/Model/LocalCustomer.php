<?php

/**
 * Customer class.
 *
 * Model class for customer
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
use Acelle\Model\Customer as MasterCustomer;

class LocalCustomer extends Customer
{
    protected $connection = 'set-it-to-a-dummy-value-to-avoid-falling-back-to-the-connection-of-the-caller-class';

    protected $table = 'customers';

    public function mailLists()
    {
        return $this->hasMany('Acelle\Model\MailList', 'customer_id');
    }

    public function lists()
    {
        return $this->hasMany('Acelle\Model\MailList', 'customer_id');
    }

    public function campaigns()
    {
        return $this->hasMany('Acelle\Model\Campaign', 'customer_id');
    }

    public function sentCampaigns()
    {
        return $this->hasMany('Acelle\Model\Campaign', 'customer_id')->where('status', '=', 'done')->orderBy('created_at', 'desc');
    }

    public function subscribers()
    {
        return $this->hasManyThrough('Acelle\Model\Subscriber', 'Acelle\Model\MailList', 'customer_id');
    }

    public function trackingLogs()
    {
        return $this->hasMany('Acelle\Model\TrackingLog', 'customer_id')->orderBy('created_at', 'asc');
    }

    public function automation2s()
    {
        return $this->hasMany('Acelle\Model\Automation2', 'customer_id');
    }

    public function activeAutomation2s()
    {
        return $this->hasMany('Acelle\Model\Automation2', 'customer_id')->where('status', Automation2::STATUS_ACTIVE);
    }

    public function sendingDomains()
    {
        return $this->hasMany('Acelle\Model\SendingDomain', 'customer_id');
    }

    // Only direct senders children
    public function senders()
    {
        return $this->hasMany('Acelle\Model\Sender', 'customer_id');
    }

    // tracking domain
    public function trackingDomains()
    {
        return $this->hasMany('Acelle\Model\TrackingDomain', 'customer_id');
    }

    public function products()
    {
        return $this->hasMany('Acelle\Model\Product', 'customer_id');
    }

    public function forms()
    {
        return $this->hasMany('Acelle\Model\Form', 'customer_id');
    }

    public function websites()
    {
        return $this->hasMany('Acelle\Model\Website', 'customer_id');
    }

    /**
     * Get user sources.
     */
    public function sources()
    {
        return $this->hasmany('Acelle\Model\Source', 'customer_id');
    }

    public function master()
    {
        return MasterCustomer::on('mysql')->where('uid', $this->uid)->first();
    }

    public static function sync(MasterCustomer $masterCustomer)
    {

        if (!$masterCustomer->hasLocalDb()) {
            throw new \Exception("Master customer #{$masterCustomer->id} '{$masterCustomer->name}' does not have a local db configured in 'db_connection'");
        }

        $localCustomer = static::on($masterCustomer->db_connection)->find($masterCustomer->id);

        if (!is_null($localCustomer)) {
            throw new \Exception("Master customer #{$masterCustomer->id} '{$masterCustomer->name}' already has a local instance at '{$masterCustomer->db_connection}'");
        }

        $localCustomer = new static();
        $localCustomer->id = $masterCustomer->id;
        $localCustomer->uid = $masterCustomer->uid;

        return $localCustomer->setConnection($masterCustomer->db_connection)->save();
    }
}
