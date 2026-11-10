@php
    $siteSetting = \App\Models\SiteSetting::query()->first();
@endphp

<x-filament-panels::page.simple class="admin-login-page">
    <style>
        .fi-body:has(.admin-login-page) {
            background-color: #f1f7fb;
        }

        .fi-body:has(.admin-login-page) .fi-simple-main {
            margin-block: 2rem;
            border: 1px solid #d8e6f0;
            border-top: 4px solid #0066b2;
            border-radius: 1.125rem;
            padding: 2.5rem;
            box-shadow: 0 24px 60px -42px rgb(15 23 42 / 35%);
        }

        .admin-login-page .fi-simple-header {
            gap: 0.25rem;
        }

        .admin-login-page .fi-logo {
            max-width: 14rem;
            object-fit: contain;
        }

        .admin-login-page .fi-simple-header-heading {
            color: #102a43;
        }

        .admin-login-page .fi-simple-header-subheading {
            color: #52677b;
            line-height: 1.6;
        }

        .admin-login-intro {
            margin-bottom: 1.25rem;
            border-left: 3px solid #0066b2;
            border-radius: 0.5rem;
            background: #f2f8fc;
            padding: 0.8rem 1rem;
            color: #52677b;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .admin-login-footer {
            margin-top: 1.5rem;
            border-top: 1px solid #e5edf3;
            padding-top: 1rem;
            color: #64748b;
            font-size: 0.75rem;
            line-height: 1.5;
            text-align: center;
        }

        @media (max-width: 640px) {
            .fi-body:has(.admin-login-page) .fi-simple-main {
                margin: 1rem 0.75rem;
                padding: 1.75rem 1.25rem;
            }
        }
    </style>

    <x-slot name="heading">
        {{ $this->getHeading() }}
    </x-slot>

    <x-slot name="subheading">
        {{ $siteSetting?->nama_instansi ?? 'Dinas Pariwisata dan Ekonomi Kreatif' }}<br>
        {{ $siteSetting?->nama_daerah ?? 'Kabupaten Merauke' }}
    </x-slot>

    <div class="admin-login-intro">
        Masuk menggunakan akun admin untuk mengelola publikasi, destinasi, dan data statistik.
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    <p class="admin-login-footer">
        Portal administrasi {{ $siteSetting?->nama_daerah ?? 'Kabupaten Merauke' }}
    </p>
</x-filament-panels::page.simple>