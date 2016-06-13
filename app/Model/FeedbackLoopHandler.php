<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class FeedbackLoopHandler extends DeliveryHandler
{
    protected $table = 'delivery_handlers';

    public function processMessage($mbox, $message) {
        $header = imap_headerinfo($mbox, $message);
                
        $output = $header->subject." : ";
        $output .= $header->toaddress." : ";
        $output .= $header->Msgno." : ";
        $headers = imap_fetchheader($mbox, $header->Msgno);
        preg_match('/Received\s*:\s*[^\n]*/im', $headers, $matched);
        
        if (!empty($matched)) {
            $type = preg_replace('/Received\s*:\s*/im', '', $matched[0]);
            echo "\n".$type."\n";
        } else {
            // not an ARF email
        }
        
        echo "\n...............................\n";
    }
}
