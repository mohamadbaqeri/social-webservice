<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermissionsTraits;
use Illuminate\Support\Facades\DB;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function AdminView(User $user)
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Post $post)
    {

        return $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function editUser(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\user $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updatePost(User $user, Post $post)
    {
            return $user->id == $post->user_id;
    }

    public function updateComment(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id;
    }

    public function deleteComment(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\user $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, user $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\user $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, user $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\user $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, user $model)
    {
        //
    }
}
