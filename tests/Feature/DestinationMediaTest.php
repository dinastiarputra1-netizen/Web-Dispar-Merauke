<?php

namespace Tests\Feature;

use App\Models\Destination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestinationMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_destination_detail_shows_youtube_video_and_up_to_three_gallery_photos(): void
    {
        $destination = Destination::query()->create([
            'title' => 'Taman Uji',
            'slug' => 'taman-uji',
            'type' => 'wisata',
            'status' => 'published',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'gallery_images' => [
                'destinations/gallery/photo-1.jpg',
                'destinations/gallery/photo-2.jpg',
                'destinations/gallery/photo-3.jpg',
                'destinations/gallery/photo-4.jpg',
            ],
        ]);

        $response = $this->get('/publikasi/ragam-wisata/'.$destination->slug);

        $response->assertOk()
            ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ')
            ->assertSee('destinations/gallery/photo-1.jpg')
            ->assertSee('destinations/gallery/photo-2.jpg')
            ->assertSee('destinations/gallery/photo-3.jpg')
            ->assertDontSee('destinations/gallery/photo-4.jpg');
    }

    public function test_legacy_destination_without_video_keeps_its_featured_image(): void
    {
        $destination = Destination::query()->create([
            'title' => 'Wisata Lama',
            'slug' => 'wisata-lama',
            'type' => 'wisata',
            'status' => 'published',
            'featured_image' => 'destinations/legacy-featured.jpg',
        ]);

        $response = $this->get('/publikasi/ragam-wisata/'.$destination->slug);

        $response->assertOk()
            ->assertSee('destinations/legacy-featured.jpg')
            ->assertDontSee('youtube-nocookie.com/embed');
    }

    public function test_creative_economy_detail_shows_youtube_video_and_gallery(): void
    {
        $destination = Destination::query()->create([
            'title' => 'Kerajinan Uji',
            'slug' => 'kerajinan-uji',
            'type' => 'ekraf',
            'status' => 'published',
            'youtube_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'gallery_images' => ['ekraf/photo-1.jpg', 'ekraf/photo-2.jpg', 'ekraf/photo-3.jpg'],
        ]);

        $response = $this->get('/publikasi/ragam-ekonomi-kreatif/'.$destination->slug);

        $response->assertOk()
            ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ')
            ->assertSee('ekraf/photo-1.jpg')
            ->assertSee('ekraf/photo-2.jpg')
            ->assertSee('ekraf/photo-3.jpg');
    }
}
