<?php

namespace Tests\Feature;

use App\Models\Agenda;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendaMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_agenda_detail_shows_youtube_video_and_up_to_three_gallery_photos(): void
    {
        $agenda = Agenda::query()->create([
            'title' => 'Agenda Uji',
            'slug' => 'agenda-uji',
            'status' => 'published',
            'content' => 'Konten agenda uji.',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'gallery_images' => [
                'agendas/gallery/photo-1.jpg',
                'agendas/gallery/photo-2.jpg',
                'agendas/gallery/photo-3.jpg',
                'agendas/gallery/photo-4.jpg',
            ],
        ]);

        $response = $this->get('/publikasi/agenda/'.$agenda->slug);

        $response->assertOk()
            ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ')
            ->assertSee('agendas/gallery/photo-1.jpg')
            ->assertSee('agendas/gallery/photo-2.jpg')
            ->assertSee('agendas/gallery/photo-3.jpg')
            ->assertDontSee('agendas/gallery/photo-4.jpg')
            ->assertDontSee('maps.google.com');
    }
}
