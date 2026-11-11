<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageContentFormattingTest extends TestCase
{
    use RefreshDatabase;

    public function test_rich_text_formatting_is_rendered_on_profile_pages(): void
    {
        Page::query()->create([
            'title' => 'Profil Lembaga',
            'slug' => 'profil-lembaga',
            'content' => '<h2>Visi</h2><strong>Merauke</strong>',
        ]);
        Page::query()->create([
            'title' => 'Struktur Organisasi',
            'slug' => 'struktur-organisasi',
            'content' => '<h2>Bagan</h2><strong>Organisasi</strong>',
        ]);

        $this->get('/profil-lembaga')
            ->assertOk()
            ->assertSee('<h2>Visi</h2><strong>Merauke</strong>', false);

        $this->get('/struktur-organisasi')
            ->assertOk()
            ->assertSee('<h2>Bagan</h2><strong>Organisasi</strong>', false);
    }

    public function test_plain_text_page_content_remains_escaped(): void
    {
        Page::query()->create([
            'title' => 'Profil Lembaga',
            'slug' => 'profil-lembaga',
            'content' => '<script>alert("unsafe")</script>',
        ]);

        $this->get('/profil-lembaga')
            ->assertOk()
            ->assertSee('&lt;script&gt;alert(&quot;unsafe&quot;)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert("unsafe")</script>', false);
    }
}
