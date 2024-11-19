<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Automation2;

class Automation2Policy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        if (app_profile('automation.disable') === true) {
            return false;
        }

        if (!\Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_builder')) {
            return false;
        }

        // RBAC check
        if ($user->hasPermission('automation.all')) {
            $permission = true; 
        } else {
            $permission = false;    
        }

        if (!$user->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_builder')) {
            return false;
        }

        return $permission;
    }

    public function create(User $user, Automation2 $automation)
    {
        if (!$user->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_builder')) {
            return false;
        }

        $customer = $user->customer;
        $max = get_tmp_quota($customer, 'automation_max');

        $can = $max > $customer->automationsCount() || $max == -1;

        // RBAC check
        if ($user->hasPermission('automation.all')) {
            $permission = true;   
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function view(User $user, Automation2 $automation)
    {
        if (app_profile('automation.disable') === true) {
            return false;
        }

        if (!\Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_builder')) {
            return false;
        }

        $can = $automation->customer_id == $user->customer->id;

        // RBAC check
        if ($user->hasPermission('automation.all')) {
            $permission = true;  
        } elseif ($user->hasPermission('automation.read')) {
            $permission = true;    
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function update(User $user, Automation2 $automation)
    {
        if (app_profile('automation.disable') === true) {
            return false;
        }

        if (!\Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_builder')) {
            return false;
        }

        $can = $automation->customer_id == $user->customer->id && !$user->isReadOnly();

        // RBAC check
        if ($user->hasPermission('automation.all')) {
            $permission = true;
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }


    public function enable(User $user, Automation2 $automation)
    {
        if (app_profile('automation.disable') === true) {
            return false;
        }

        if (!\Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_builder')) {
            return false;
        }

        $can = $automation->customer_id == $user->customer->id &&
            in_array($automation->status, [
                Automation2::STATUS_INACTIVE
            ]) && !$user->isReadOnly();

        // RBAC check
        if ($user->hasPermission('automation.all')) {
            $permission = true;
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function disable(User $user, Automation2 $automation)
    {
        if (app_profile('automation.disable') === true) {
            return false;
        }

        if (!\Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_builder')) {
            return false;
        }

        $can = $automation->customer_id == $user->customer->id &&
            in_array($automation->status, [
                Automation2::STATUS_ACTIVE
            ]) && !$user->isReadOnly();

        // RBAC check
        if ($user->hasPermission('automation.all')) {
            $permission = true;
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }

    public function delete(User $user, Automation2 $automation)
    {
        if (app_profile('automation.disable') === true) {
            return false;
        }

        if (!\Auth::user()->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('automation_builder')) {
            return false;
        }

        $can = $automation->customer_id == $user->customer->id &&
            in_array($automation->status, [
                Automation2::STATUS_ACTIVE,
                Automation2::STATUS_INACTIVE
            ]) && !$user->isReadOnly();

        // RBAC check
        if ($user->hasPermission('automation.all')) {
            $permission = true;
        } else {
            $permission = false;    
        }

        return $can && $permission;
    }
}
