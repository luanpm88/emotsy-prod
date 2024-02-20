<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\MailList;

class MailListPolicy
{
    use HandlesAuthorization;

    public function read(User $user, MailList $item)
    {
        $customer = $user->customer;
        return $item->customer_id == $customer->id;
    }

    public function create(User $user)
    {
        // init
        $customer = $user->customer;
        $max = get_tmp_quota($customer, 'list_max');
        $isUnlimited = $max == -1; // -1 means unlimited
        $notReachMax = $max > $customer->lists()->count();

        // check max lists: unlimited or is not reach max
        $can = $isUnlimited || $notReachMax;

        // config/limit.php
        $limit = app_profile('list.limit');
        if (!is_null($limit)) {
            $listsCount = $user->customer->listsCount();
            $can = $can && ($listsCount < $limit);
        } else {
            // ignore limit because it is null
        }
        
        // RBAC check
        if ($user->hasPermission('list.all')) {
            $permission = true; 
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function update(User $user, MailList $item)
    {
        $customer = $user->customer;
        $can = $item->customer_id == $customer->id;

        // RBAC check
        if ($user->hasPermission('list.all')) {
            $permission = true; 
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function delete(User $user, MailList $item)
    {
        $customer = $user->customer;
        $can = $item->customer_id == $customer->id;

        // RBAC check
        if ($user->hasPermission('list.all')) {
            $permission = true;
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function addMoreSubscribers(User $user, MailList $mailList, $numberOfSubscribers = 1)
    {
        $max = get_tmp_quota($user->customer, 'subscriber_max');
        $maxPerList = get_tmp_quota($user->customer, 'subscriber_per_list_max');
        $can = $user->customer->id == $mailList->customer_id &&
            ($max >= $user->customer->subscribersCount() + $numberOfSubscribers || $max == -1) &&
            ($maxPerList >= $mailList->subscribersCount() + $numberOfSubscribers || $maxPerList == -1);

        // RBAC check
        if ($user->hasPermission('list.all')) {
            $permission = true;   
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function import(User $user, MailList $item)
    {
        $customer = $user->customer;
        $can = get_tmp_quota($customer, 'list_import');

        $can = ($can == 'yes' && $item->customer_id == $customer->id);

        // RBAC check
        if ($user->hasPermission('list.all')) {
            $permission = true;   
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function export(User $user, MailList $item)
    {
        $customer = $user->customer;
        $can = get_tmp_quota($customer, 'list_export');

        $can = ($can == 'yes' && $item->customer_id == $customer->id);

        // RBAC check
        if ($user->hasPermission('list.all')) {
            $permission = true;  
        } elseif ($user->hasPermission('list.read')) {
            $permission = true;    
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }
    
    public function embedded_form(User $user, MailList $item)
    {
        return $user->customer &&
            $user->customer->getNewOrActiveGeneralSubscription() && 
            $user->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('embedded_form');
    }
}
