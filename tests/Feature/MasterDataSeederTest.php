<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\MasterDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_the_two_predefined_static_pages_without_duplicates(): void
    {
        $this->seed(MasterDataSeeder::class);
        $this->seed(MasterDataSeeder::class);

        $this->assertDatabaseCount('pages', 2);
        $this->assertDatabaseHas('pages', [
            'slug' => 'profil-lembaga',
            'title' => 'Profil Lembaga',
        ]);
        $this->assertDatabaseHas('pages', [
            'slug' => 'struktur-organisasi',
            'title' => 'Struktur Organisasi',
        ]);
        $this->assertSame(
            'Konten Struktur Organisasi yang sudah disunting.',
            Page::query()->where('slug', 'struktur-organisasi')->value('content')
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        Page::query()->create([
            'title' => 'Struktur Organisasi',
            'slug' => 'struktur-organisasi',
            'content' => 'Konten Struktur Organisasi yang sudah disunting.',
        ]);
    }
}
