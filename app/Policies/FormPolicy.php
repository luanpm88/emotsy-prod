<?php

namespace Acelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Acelle\Model\User;
use Acelle\Model\Form;

class FormPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        if (app_profile('form.disable') === true) {
            return false;
        }

        return true;
    }

    public function create(User $user)
    {
        if (app_profile('form.disable') === true) {
            return false;
        }

        // max features
        if ($user->customer->forms()->count() > $user->customer->getNewOrActiveGeneralSubscription()->planGeneral->getFeature('popup')) {
            return false;
        }

        return true && !$user->isReadOnly();
    }

    public function read(User $user, Form $form)
    {
        if (app_profile('form.disable') === true) {
            return false;
        }

        return $user->customer->id == $form->customer_id;
    }

    public function update(User $user, Form $form)
    {
        if (app_profile('form.disable') === true) {
            return false;
        }

        return $user->customer->id == $form->customer_id && !$user->isReadOnly();
    }

    public function delete(User $user, Form $form)
    {
        if (app_profile('form.disable') === true) {
            return false;
        }

        return $user->customer->id == $form->customer_id && !$user->isReadOnly();
    }

    public function publish(User $user, Form $form)
    {
        if (app_profile('form.disable') === true) {
            return false;
        }

        return $user->customer->id == $form->customer_id && $form->status == Form::STATUS_DRAFT && !$user->isReadOnly();
    }

    public function unpublish(User $user, Form $form)
    {
        if (app_profile('form.disable') === true) {
            return false;
        }

        return $user->customer->id == $form->customer_id && $form->status == Form::STATUS_PUBLISHED && !$user->isReadOnly();
    }
}
