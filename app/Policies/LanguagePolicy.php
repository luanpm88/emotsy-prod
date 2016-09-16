<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class LanguagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function create(\Acelle\Model\User $user, \Acelle\Model\Language $item)
    {
        $can = $user->getOption('backend', 'language_create') == 'yes';

        return $can && false;
    }

    public function update(\Acelle\Model\User $user, \Acelle\Model\Language $item)
    {
        $ability = $user->getOption('backend', 'language_update');
        $can = $ability == 'yes';

        return $can && false;
    }

    public function delete(\Acelle\Model\User $user, \Acelle\Model\Language $item)
    {
        $ability = $user->getOption('backend', 'language_delete');
        $can = $ability == 'yes';

        return $can && false;
    }
}
