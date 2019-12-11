<?php

namespace Acelle\Library\Automation;

use Carbon\Carbon;

class Evaluate extends Action
{
    protected $childYes;
    protected $childNo;

    function __construct($params = [])
    {
        parent::__construct($params);

        $this->childYes = array_key_exists('childYes', $params) ? $params['childYes'] : null;
        $this->childNo = array_key_exists('childNo', $params) ? $params['childNo'] : null;
    }

    public function toJson()
    {
        $json = parent::toJson();
        $json = array_merge( $json, [
            'childYes' => $this->childYes,
            'childNo' => $this->childNo
        ]);

        return $json;
    }

    public function execute()
    {
        $this->setOption('checked_at', Carbon::now()->timestamp);
        return true;
    }
}
