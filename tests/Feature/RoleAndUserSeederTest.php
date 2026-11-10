<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RoleAndUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_three_role_specific_admins_without_duplicates(): void
    {
        config([
            'admin.accounts.content.email' => 'content-admin@example.test',
            'admin.accounts.content.password' => 'content-secret-for-test',
            'admin.accounts.data.email' => 'data-admin@example.test',
            'admin.accounts.data.password' => 'data-secret-for-test',
        ]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->seed(RoleAndUserSeeder::class);
        $this->seed(RoleAndUserSeeder::class);

        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseCount('roles', 3);
        $this->assertTrue(User::where('email', 'admin@dispar.merauke.go.id')->first()->hasRole('Super Admin'));
        $this->assertTrue(User::where('email', 'content-admin@example.test')->first()->hasRole('Admin Konten'));
        $this->assertTrue(User::where('email', 'data-admin@example.test')->first()->hasRole('Admin Data'));
    }
}
