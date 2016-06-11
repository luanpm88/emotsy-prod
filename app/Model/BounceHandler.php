<?php

namespace Mailixa\Model;

use Illuminate\Database\Eloquent\Model;

class BounceHandler extends DeliveryHandler
{
    protected $table = 'delivery_handlers';

    public function processMessage($mbox, $message) {
       $header = imap_headerinfo($mbox, $message);
                
       $output = $header->subject." : ";
       $output .= $header->toaddress." : ";
       $output .= $header->Msgno." : ";
       echo $output . "\n...............................\n";

       imap_delete($mbox, $header->Msgno);
    }

    public function getBouncedAddress($to) {
       preg_match('/(?<=\+)[^\+]+=[^@]+(?=@)/', $to, $matched);
       if (sizeof($matched) == 0) {
           return;
       } else {
           return $matched[0];
       }
    }
}
