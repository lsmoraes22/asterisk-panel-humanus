namespace App\Traits;

trait HasTenantPermissions
{
    public function hasTenantPermission(string $permission): bool
    {
        $tenant = tenant(); // helper global

        if (!$tenant) {
            return false;
        }

        return $tenant->permissions()
            ->where('permission', $permission)
            ->exists();
    }
}
