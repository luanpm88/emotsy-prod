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
        return true;
    }
    
    public function update(\Mailixa\Model\User $user, \Mailixa\Model\Language $item)
    {
        return true;
    }
    
    public function delete(\Mailixa\Model\User $user, \Mailixa\Model\Language $item)
    {
        return true;
    }
}
