<?php

namespace Acelle\Library\JsonModel;

use Exception;

class IdentityList extends Base
{
    protected $data;

    protected $schema = [
    	// Identity Object
    	'domain1.com' => [
    		'Name' => 'domain1.com',
			'VerificationStatus' => true,
			'UserId' => 1769,
			'UserName' => 'Napoléon Bonaparte'
		],

		// Identity Object
		'domain2.com' => [
			'Name' => 'domain2.com',
			'VerificationStatus' => false,
			'UserId' => 1810,
			'UserName' => 'Frédéric Chopin'
		]
    ];

    function __construct($data)
    {
        // Throw an exception if failing
        $this->validate($data);

        // OK
        $this->data = $data;
    }

    public function validate($data)
    {
        if (gettype($data) != 'array') {
        	throw new Exception('Invalid data type');
        }
    }
}
