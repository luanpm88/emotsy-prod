<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Form;

class FormPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Form $form)
    {
        return $user->customer->id == $form->customer_id;
    }

    public function delete(User $user, Form $form)
    {
        return $user->customer->id == $form->customer_id;
    }
}
