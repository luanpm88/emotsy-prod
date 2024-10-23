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

class LocalCustomer extends Model
{
    protected $connection = 'set-it-to-a-dummy-value-to-avoid-falling-back-to-the-connection-of-the-caller-class';

    protected $table = 'customers';

    public function master()
    {
        return $this->setConnection('mysql')->refresh();
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
