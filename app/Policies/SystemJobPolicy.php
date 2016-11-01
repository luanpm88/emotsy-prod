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
        if($item->name == 'Acelle\Jobs\ImportSubscribersJob' || $item->name == 'Acelle\Jobs\ExportSubscribersJob') {
            $list = \Acelle\Model\MailList::findByUid(json_decode($item->data)->mail_list_uid);
            return $list->user_id == $user->id &&
            in_array($item->status, ['failed', 'done']);
        }
        
        return false;
    }
    
    public function downloadImportLog(\Acelle\Model\User $user, \Acelle\Model\SystemJob $item)
    {
        $list = \Acelle\Model\MailList::findByUid(json_decode($item->data)->mail_list_uid);
        return $list->user_id == $user->id &&
            $item->name == 'Acelle\Jobs\ImportSubscribersJob' &&
            $item->status == 'done';
    }    
    
    public function downloadExportCsv(\Acelle\Model\User $user, \Acelle\Model\SystemJob $item)
    {
        $list = \Acelle\Model\MailList::findByUid(json_decode($item->data)->mail_list_uid);
        return $list->user_id == $user->id &&
            $item->name == 'Acelle\Jobs\ExportSubscribersJob' &&
            $item->status == 'done';
    }
}
