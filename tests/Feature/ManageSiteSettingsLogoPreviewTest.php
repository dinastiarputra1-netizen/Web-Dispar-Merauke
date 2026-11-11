<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class ManageSiteSettingsLogoPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_saved_logo_preview_uses_the_current_request_host(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('settings/logo.webp', 'image-data');

        SiteSetting::query()->create([
            'nama_instansi' => 'Dinas Pariwisata dan Ekonomi Kreatif',
            'nama_daerah' => 'Kabupaten Merauke',
            'logo_dinas' => 'settings/logo.webp',
        ]);

        Filament::setCurrentPanel(Filament::getPanel('Admin-Dispar'));
        URL::forceRootUrl('http://admin.localhost:8000');

        Livewire::test(ManageSiteSettings::class)
            ->call('getFormUploadedFiles', 'data.logo_dinas')
            ->assertReturned(static function (array $files): bool {
                return array_values($files)[0] === [
                    'name' => 'logo.webp',
                    'size' => strlen('image-data'),
                    'type' => 'image/webp',
                    'url' => 'http://admin.localhost:8000/storage/settings/logo.webp',
                ];
            });
    }
}
