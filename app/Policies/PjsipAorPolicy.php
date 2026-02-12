<?php

namespace App\Policies;

use App\Models\PjsipAor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PjsipAorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
	return $user->hasRole('super_admin') || $user->tenant_id != null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PjsipAor $pjsipAor): bool
    {
	return $user->hasRole('super_admin') || $user->tenant_id === $pjsipAor->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
	return $user->hasRole('super_admin') || $user->tenant_id != null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PjsipAor $pjsipAor): bool
    {
	return $user->hasRole('super_admin') || $user->tenant_id === $pjsipAor->tenant_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PjsipAor $pjsipAor): bool
    {
	return $user->hasRole('super_admin') || $user->tenant_id === $pjsipAor->tenant_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PjsipAor $pjsipAor): bool
    {
	return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PjsipAor $pjsipAor): bool
    {
	return $user->hasRole('super_admin');
    }
}
