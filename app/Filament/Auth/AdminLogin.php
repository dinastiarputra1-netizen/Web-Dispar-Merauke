<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Login;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class AdminLogin extends Login
{
    protected static string $view = 'filament.auth.admin-login';

    protected static ?string $title = 'Masuk Admin';

    public function getHeading(): string|Htmlable
    {
        return 'Masuk ke Dashboard Admin';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Masuk Admin';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Kelola informasi wisata, ekonomi kreatif, dan statistik Kabupaten Merauke.';
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()->label('Alamat email');
    }

    protected function getPasswordFormComponent(): Component
    {
        $component = parent::getPasswordFormComponent()->label('Kata sandi');

        if (filament()->hasPasswordReset()) {
            $component->hint(new HtmlString(Blade::render(
                '<x-filament::link :href="filament()->getRequestPasswordResetUrl()" tabindex="3">Lupa kata sandi?</x-filament::link>'
            )));
        }

        return $component;
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()->label('Ingat saya');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()->label('Masuk');
    }
}
