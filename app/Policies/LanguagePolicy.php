<?php

namespace Mailixa\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class LanguagePolicy
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
    
    public function create(\Mailixa\Model\User $user, \Mailixa\Model\Language $item)
    {
        $can = $user->getOption("backend", "language_create") == 'yes';
        return $can;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Language $item)
    {
        $ability = $user->getOption("backend", "language_update");
        $can = $ability == 'yes';
        return $can;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\Language $item)
    {
        $ability = $user->getOption("backend", "language_delete");
        $can = $ability == 'yes';
        return $can;
    }
}
