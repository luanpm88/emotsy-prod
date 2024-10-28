<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function read(User $user)
    {
        $can = $user->admin->getPermission('user_read') != 'no';

        return $can;
    }

    public function read_all(User $user)
    {
        $can = $user->admin->getPermission('user_read') == 'all';

        return $can;
    }

    public function create(User $user, User $item, $role='admin')
    {
        // @important: in case of non-saas mode, it falls upon admin case
        switch ($role) {
            case 'admin':
                $can = true;
                break;
            case 'customer':
                $can = $user->id != $item->id;
                
                // RBAC check
                if ($user->hasPermission('account.full_access')) {
                    $permission = true;   
                } else {
                    $permission = false;    
                }

                $can = $can && $permission;

                break;
        }
        

        return $can;
    }

    public function profile(User $user, User $item)
    {
        return $user->id == $item->id;
    }

    public function update(User $user, User $item, $role='admin')
    {
        // @important: in case of non-saas mode, it falls upon admin case
        switch ($role) {
            case 'admin':
                $ability = 'all';
                $can = $ability == 'all'
                        || ($ability == 'own' && $user->id == $item->id);
                break;
            case 'customer':
                // RBAC check
                if ($user->hasPermission('account.full_access')) {
                    $permission = true;   
                } else {
                    $permission = false;    
                }

                $can = $permission;
                break;
        }
        return $can;
    }

    public function disable(User $user, User $item, $role='admin')
    {
        // @important: in case of non-saas mode, it falls upon admin case
        switch ($role) {
            case 'admin':
                $ability = 'all';
                $can = $ability == 'all'
                        || ($ability == 'own' && $user->id == $item->id);
                break;
            case 'customer':
                $can = $user->id != $item->id && $item->isActivated();
                
                // RBAC check
                if ($user->hasPermission('account.full_access')) {
                    $permission = true;   
                } else {
                    $permission = false;    
                }

                $can = $can && $permission;

                break;
        }
        return $can;
    }

    public function enable(User $user, User $item, $role='admin')
    {
        // @important: in case of non-saas mode, it falls upon admin case
        switch ($role) {
            case 'admin':
                $ability = 'all';
                $can = $ability == 'all'
                        || ($ability == 'own' && $user->id == $item->id);
                break;
            case 'customer':
                $can = $user->id != $item->id && !$item->isActivated();

                // RBAC check
                if ($user->hasPermission('account.full_access')) {
                    $permission = true;   
                } else {
                    $permission = false;    
                }

                $can = $can && $permission;

                break;
        }
        return $can;
    }

    public function delete(User $user, User $item, $role='admin')
    {
        // @important: in case of non-saas mode, it falls upon admin case
        switch ($role) {
            case 'admin':
                $ability = 'all';
                $can = $ability == 'all'
                        || ($ability == 'own' && $user->id == $item->id);
                $can = $can && $user->id != $item->id;
                break;
            case 'customer':
                $can = $user->id != $item->id;
                
                // RBAC check
                if ($user->hasPermission('account.full_access')) {
                    $permission = true;   
                } else {
                    $permission = false;    
                }

                $can = $can && $permission;
                
                break;
        }
        return $can;
    }

    public function customer_access(User $user)
    {
        return !is_null($user->customer);
    }

    public function admin_access(User $user)
    {
        return !is_null($user->admin);
    }

    public function reseller_access(User $user)
    {
        return !is_null($user->reseller);
    }

    public function change_group(User $user)
    {
        $ability = $user->admin->getPermission('user_update');
        $can = $ability == 'all';

        return $can;
    }

    public function loginAs(User $user, User $item, $role='admin')
    {
        // @important: in case of non-saas mode, it falls upon admin case
        switch ($role) {
            case 'admin':
                $ability = $user->admin->getPermission('customer_login_as');
                $can = $item->id != $user->customer_id && ($ability == 'all');
                break;
            case 'customer':
                $can = $user->id != $item->id;

                // RBAC check
                if ($user->hasPermission('account.full_access')) {
                    $permission = true;   
                } else {
                    $permission = false;    
                }

                $can = $can && $permission;

                break;
        }
        return $can;
    }
}
