<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class DeliveryHandler extends Model
{
    const TYPE_BOUNCE = 'bounce';
    const TYPE_FEEDBACK_LOOP = 'feedback';

    public function getBouncedAddress($to) {
        preg_match('/(?<=\+)[^\+]+=[^@]+(?=@)/', $to, $matched);
        if (sizeof($matched) == 0) {
            return;
        } else {
            return $matched[0];
        }
    }

    public function start() { 
        // Connect to IMAP server
        $imapPath = "{{$this->host}:{$this->port}/{$this->protocol}/{$this->encryption}}INBOX";
         
        // try to connect 
        $inbox = imap_open($imapPath, $this->username, 'xxx') or die('Cannot connect to Gmail: ' . imap_last_error());
         
        // search and get unseen emails, function will return email ids
        $emails = imap_search($inbox, 'UNSEEN');
        
        if (!empty($emails)) {
            foreach($emails as $mail) {
                
                $header = imap_headerinfo($inbox, $mail);
                
                $output = $header->subject." : ";
                $output .= $header->toaddress." : ";
                $output .= $header->Msgno." : ";
                echo $output . "\n...............................\n";

                imap_delete($inbox, $header->Msgno);
            }
        }
         
        // colse the connection
        imap_expunge($inbox);
        imap_close($inbox);
    }
}
