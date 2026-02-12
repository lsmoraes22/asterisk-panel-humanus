<?php

namespace App\Policies;

use App\Models\DidNumber;
use App\Models\User;

class DidNumberPolicy
{
    // Super Admin pode tudo, outros apenas o que pertence ao seu Tenant
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_did::number');
    }

    public function view(User $user, DidNumber $didNumber): bool
    {
        return $user->hasRole('super_admin') || $user->tenant_id === $didNumber->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->can('create_did::number');
    }

    public function update(User $user, DidNumber $didNumber): bool
    {
        return $user->hasRole('super_admin') || $user->tenant_id === $didNumber->tenant_id;
    }

    public function delete(User $user, DidNumber $didNumber): bool
    {
        return $user->hasRole('super_admin') || $user->tenant_id === $didNumber->tenant_id;
    }
}