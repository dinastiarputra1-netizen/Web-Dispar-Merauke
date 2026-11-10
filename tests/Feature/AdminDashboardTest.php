<?php

namespace Tests\Feature;

use App\Filament\Widgets\DashboardOverview;
use App\Models\Agenda;
use App\Models\CreativeEconomyGroup;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_live_content_summaries(): void
    {
        Destination::query()->create([
            'title' => 'Wisata Uji',
            'slug' => 'wisata-uji',
            'type' => 'wisata',
            'status' => 'published',
        ]);
        Destination::query()->create([
            'title' => 'Ekraf Uji',
            'slug' => 'ekraf-uji',
            'type' => 'ekraf',
            'status' => 'published',
        ]);
        Destination::query()->create([
            'title' => 'Wisata Draft',
            'slug' => 'wisata-draft',
            'type' => 'wisata',
            'status' => 'draft',
        ]);

        Agenda::query()->create([
            'title' => 'Agenda Terbit',
            'slug' => 'agenda-terbit',
            'content' => 'Konten agenda',
            'status' => 'published',
        ]);
        Agenda::query()->create([
            'title' => 'Agenda Draft',
            'slug' => 'agenda-draft',
            'content' => 'Konten agenda draft',
            'status' => 'draft',
        ]);

        Hotel::query()->create(['name' => 'Hotel Aktif', 'is_active' => true]);
        Hotel::query()->create(['name' => 'Hotel Tidak Aktif', 'is_active' => false]);
        Restaurant::query()->create(['business_name' => 'Restoran Aktif', 'is_active' => true]);

        CreativeEconomyGroup::query()->create([
            'year' => 2026,
            'group_name' => 'Kelompok OAP',
            'classification' => 'OAP',
        ]);
        CreativeEconomyGroup::query()->create([
            'year' => 2026,
            'group_name' => 'Kelompok Non-OAP',
            'classification' => 'Non-OAP',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get('http://admin.localhost/admin');

        $response->assertOk();

        $getStats = new \ReflectionMethod(DashboardOverview::class, 'getStats');
        $stats = $getStats->invoke(app(DashboardOverview::class));

        $this->assertSame('2', $stats[0]->getValue());
        $this->assertSame('1 wisata · 1 ekonomi kreatif', $stats[0]->getDescription());
        $this->assertSame('1', $stats[1]->getValue());
        $this->assertSame('1', $stats[2]->getValue());
        $this->assertSame('1', $stats[3]->getValue());
        $this->assertSame('2', $stats[4]->getValue());
        $this->assertSame('1 OAP · 1 Non-OAP', $stats[4]->getDescription());
        $this->assertSame('2', $stats[5]->getValue());
        $this->assertSame('1 destinasi · 1 agenda', $stats[5]->getDescription());
    }

    public function test_users_without_an_admin_role_cannot_access_the_admin_panel(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get('http://admin.localhost/admin');

        $response->assertForbidden();
    }
}
