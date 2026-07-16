<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            'auth:register',
            'auth:login',
            'auth:logout',
            'auth:refresh',
            'auth:me',
            'auth:email-verify',
            'auth:password-forgot',
            'auth:password-reset',
        ]);

        $permissions->each(function (string $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        });

        $roleNames = ['super_admin', 'admin', 'instructor', 'student'];

        $roles = collect($roleNames)->map(function (string $roleName) {
            return Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        });

        // Grant all auth permissions to super admin
        $superAdmin = $roles->firstWhere('name', 'super_admin');
        if ($superAdmin) {
            $superAdmin->syncPermissions($permissions->all());
        }

        // Assign super admin to the first user (if any)
        $user = User::query()->orderBy('id')->first();
        if ($user && $superAdmin) {
            $user->syncRoles([$superAdmin->name]);
        }
    }
}

