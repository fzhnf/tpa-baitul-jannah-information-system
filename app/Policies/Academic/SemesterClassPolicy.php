<?php

namespace App\Policies\Academic;

use App\Models\User;
use App\Models\Academic\SemesterClass;
use Illuminate\Auth\Access\HandlesAuthorization;

class SemesterClassPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_academic::semester::class');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SemesterClass $semesterClass): bool
    {
        return $user->can('view_academic::semester::class');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_academic::semester::class');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SemesterClass $semesterClass): bool
    {
        return $user->can('update_academic::semester::class');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SemesterClass $semesterClass): bool
    {
        return $user->can('delete_academic::semester::class');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_academic::semester::class');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, SemesterClass $semesterClass): bool
    {
        return $user->can('force_delete_academic::semester::class');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_academic::semester::class');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, SemesterClass $semesterClass): bool
    {
        return $user->can('restore_academic::semester::class');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_academic::semester::class');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, SemesterClass $semesterClass): bool
    {
        return $user->can('replicate_academic::semester::class');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_academic::semester::class');
    }
}
