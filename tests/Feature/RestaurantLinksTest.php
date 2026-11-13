<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_restaurant_directory_shows_website_and_google_maps_links(): void
    {
        Restaurant::query()->create([
            'business_name' => 'Restoran Uji',
            'business_type' => 'Restaurant/Rumah Makan',
            'website_url' => 'https://restoran.example.com',
            'maps_url' => 'https://maps.google.com/?q=Restoran+Uji',
            'is_active' => true,
        ]);

        $response = $this->get('/statistik/akomodasi/restoran');

        $response->assertOk()
            ->assertSee('https://restoran.example.com')
            ->assertSee('https://maps.google.com/?q=Restoran+Uji')
            ->assertSee('Kunjungi Website Resmi')
            ->assertSee('Buka Lokasi Google Maps');
    }
}
