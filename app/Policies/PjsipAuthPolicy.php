<?php

namespace App\Policies;

use App\Models\PjsipAuth;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PjsipAuthPolicy
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
    public function view(User $user, PjsipAuth $pjsipAuth): bool
    {
	return $user->hasRole('super_admin') || $user->tenant_id === $pjsipAuth->tenant_id;
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
    public function update(User $user, PjsipAuth $pjsipAuth): bool
    {
	return $user->hasRole('super_admin') || $user->tenant_id === $pjsipAuth->tenant_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PjsipAuth $pjsipAuth): bool
    {
	return $user->hasRole('super_admin') || $user->tenant_id === $pjsipAuth->tenant_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PjsipAuth $pjsipAuth): bool
    {
	return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PjsipAuth $pjsipAuth): bool
    {
	return $user->hasRole('super_admin');
    }
}
