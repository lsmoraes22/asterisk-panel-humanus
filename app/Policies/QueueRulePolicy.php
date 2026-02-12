<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QueueRule;

class QueueRulePolicy
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
    public function view(User $user, QueueRule $queueRule): bool
    {
        return $user->hasRole('super_admin') || $user->tenant_id === $queueRule->tenant_id;
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
    public function update(User $user, QueueRule $queueRule): bool
    {
        return $user->hasRole('super_admin') || $user->tenant_id === $queueRule->tenant_id;
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QueueRule $queueRule): bool
    {
        return $user->hasRole('super_admin') || $user->tenant_id === $queueRule->tenant_id;
    }
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, QueueRule $queueRule): bool
    {
        return $user->hasRole('super_admin') || $user->tenant_id === $queueRule->tenant_id;
    }
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, QueueRule $queueRule): bool
    {
        return $user->hasRole('super_admin') || $user->tenant_id === $queueRule->tenant_id;
    }    
}