<?php

namespace App\Policies\Academic;

use App\Models\User;
use App\Models\Academic\ClassSession;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassSessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_academic::class::session');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassSession $classSession): bool
    {
        return $user->can('view_academic::class::session');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_academic::class::session');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassSession $classSession): bool
    {
        return $user->can('update_academic::class::session');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassSession $classSession): bool
    {
        return $user->can('delete_academic::class::session');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_academic::class::session');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ClassSession $classSession): bool
    {
        return $user->can('force_delete_academic::class::session');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_academic::class::session');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ClassSession $classSession): bool
    {
        return $user->can('restore_academic::class::session');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_academic::class::session');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ClassSession $classSession): bool
    {
        return $user->can('replicate_academic::class::session');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_academic::class::session');
    }
}
