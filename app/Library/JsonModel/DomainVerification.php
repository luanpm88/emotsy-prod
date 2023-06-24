<?php

namespace Acelle\Library\JsonModel;

use Exception;

class DomainVerification 
{
    protected $json;

    function __construct($json)
    {

    }

    public function validate()
    {
        throw new Exception('Invalid JSON structure');
    }

    function toJson()
    {
        return $this->json;
    }
}
