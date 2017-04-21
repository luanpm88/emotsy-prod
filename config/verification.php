<?php

return [
    'services' => [
        [
            'id' => 'kickbox',
            'name' => 'Kickbox',
            'uri' => 'https://api.kickbox.io/v2/verify?email={EMAIL}&apikey={API_KEY}',
            'request_type' => 'GET',
            'result_xpath' => '$.result',
            'result_map' => [ 'deliverable' => 'deliverable', 'undeliverable' => 'undeliverable', 'risky' => 'risky', 'unknonw' => 'unknown' ]
        ]
    ]
];
