<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Comment $comment
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
     */
    public function delete(User $user, Comment $comment)
    {
        return $user->isAdmin();
    }
}
