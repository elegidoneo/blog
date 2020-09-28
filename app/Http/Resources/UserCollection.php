<?php

namespace App\Http\Resources;

class UserCollection extends AbstractCollection
{
    use WithResource;

    /**
     * {@inheritDoc}
     */
    protected function setData($user)
    {
        return [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "admin" => $user->isAdmin(),
            "active" => boolval($user->active),
            "token" => $this->extraUserData($user),
        ];
    }
}
