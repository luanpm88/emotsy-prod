<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SystemJobPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function importSubscriber(\Acelle\Model\User $user, \Acelle\Model\SystemJob $item)
    {
        $list = \Acelle\Model\MailList::findByUid($item->item_id);
        return $item->user_id == $user->id &&
            $list->user_id == $user->id &&
            $item->type == 'import-subscriber';
    }
    
    public function exportSubscriber(\Acelle\Model\User $user, \Acelle\Model\SystemJob $item)
    {
        $list = \Acelle\Model\MailList::findByUid($item->item_id);
        return $item->user_id == $user->id &&
            $list->user_id == $user->id &&
            $item->type == 'export-subscriber';
    }
    
    public function delete(\Acelle\Model\User $user, \Acelle\Model\SystemJob $item)
    {
        $list = \Acelle\Model\MailList::findByUid($item->item_id);
        return $item->user_id == $user->id &&
        in_array($item->status, ['error', 'finished']);
    }
    
    public function downloadLog(\Acelle\Model\User $user, \Acelle\Model\SystemJob $item)
    {
        $list = \Acelle\Model\MailList::findByUid($item->item_id);
        return $item->user_id == $user->id &&
            $item->type == 'import-subscriber' &&
            $item->status == 'finished';
    }    
    
    public function downloadCsv(\Acelle\Model\User $user, \Acelle\Model\SystemJob $item)
    {
        $list = \Acelle\Model\MailList::findByUid($item->item_id);
        return $item->user_id == $user->id &&
            $item->type == 'export-subscriber' &&
            $item->status == 'finished';
    }
}
