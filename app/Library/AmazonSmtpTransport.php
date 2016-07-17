<?php

namespace Mailixa\Library;

class AmazonSmtpTransport extends \Swift_SmtpTransport
{
    private $rawResponses = array();

    public function executeCommand($command, $codes = array(), &$failures = null)
    {
        $response = parent::executeCommand($command, $codes, $failures);
        $this->rawResponses[] = $response;
        return $response;
    }

    public static function newInstance($host = 'localhost', $port = 25, $security = null)
    {
        return new self($host, $port, $security);
    }

    public function getMessageId() 
    {
        $messageId = NULL;
        foreach($this->rawResponses as $e) {
            preg_match('/(?<=250 ok\s).*/i', $e, $matched);
            if (sizeof($matched) > 0)
                $messageId = $matched[0];
        }

        return $messageId;

    }
}
