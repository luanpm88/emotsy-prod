<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;
use Mailixa\Library\Log;

class DeliveryHandler extends Model
{
    public function start() { 
        try {
            // Connect to IMAP server
            $imapPath = "{{$this->host}:{$this->port}/{$this->protocol}/{$this->encryption}}INBOX";
            
            // try to connect 
            $inbox = @imap_open($imapPath, $this->username, $this->password);

            // search and get unseen emails, function will return email ids
            $emails = imap_search($inbox, 'UNSEEN');
            
            if (!empty($emails)) {
                foreach($emails as $message) {
                    $this->processMessage($inbox, $message);
                }
            }
             
            // colse the connection
            imap_expunge($inbox);
            imap_close($inbox);
        } catch (\Exception $e) {
            // suppress the IMAP error 
            // see http://stackoverflow.com/questions/5422405/cant-silence-imap-open-error-notices-in-php
            imap_errors();
            imap_alerts();
            Log::error('Cannot connect to handler ' . $e->getMessage());
        }
    }

    public function getMessageId($message) {
        preg_match('/(?<=X-Mailixa-Message-Id: )[^\s]*/', $message, $matched);
        if (sizeof($matched) == 0) {
            return;
        } else {
            return $matched[0];
        }
    }

    public function processMessage($mbox, $message) {
        // for overwriting
    }
}
