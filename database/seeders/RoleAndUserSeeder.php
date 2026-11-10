<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);
        Role::firstOrCreate(['name' => 'Admin Konten', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin Data', 'guard_name' => 'web']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@dispar.merauke.go.id'],
            [
                'name' => 'Administrator Dispar',
                'password' => Hash::make('password123'),
            ]
        );

        $admin->assignRole($superAdminRole);

        foreach (config('admin.accounts', []) as $account) {
            if (blank($account['email']) && blank($account['password'])) {
                continue;
            }

            if (blank($account['email']) || blank($account['password'])) {
                throw new \InvalidArgumentException('Set email and password for each configured admin account.');
            }

            $role = Role::firstOrCreate([
                'name' => $account['role'],
                'guard_name' => 'web',
            ]);

            $user = User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make($account['password']),
                ]
            );

            $user->assignRole($role);
        }
    }
}
