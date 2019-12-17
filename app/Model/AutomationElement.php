<?php

/**
 * Automation class.
 *
 * Model for automation elements
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

use Acelle\Model\Automation2;

class AutomationElement
{
    protected $data;
    
    /**
     * Constructor.
     *
     * @return the associated automation2
     */
    function __construct($data) {
        $this->data = $data;
    }
    
    /**
     * Get value.
     *
     * @return string
     */
    public function getOption($name)
    {
        if (isset($this->data) && isset($this->data->options) && isset($this->data->options->$name)) {
            return $this->data->options->$name;
        }
        
        return null;
    }
}