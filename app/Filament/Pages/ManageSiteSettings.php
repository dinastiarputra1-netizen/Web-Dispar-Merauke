<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Pengaturan Situs';

    protected static ?string $navigationLabel = 'Informasi Instansi';

    protected static ?string $title = 'Informasi & Identitas Instansi';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = SiteSetting::first();
        if ($setting) {
            $this->form->fill($setting->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Logo & Identitas Instansi')
                    ->schema([
                        Forms\Components\TextInput::make('nama_instansi')
                            ->label('Nama Instansi / Dinas')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('nama_daerah')
                            ->label('Nama Daerah')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('logo_dinas')
                            ->label('Logo Dinas Pariwisata')
                            ->image()
                            ->directory('settings'),
                        Forms\Components\FileUpload::make('logo_pariwisata')
                            ->label('Logo Pariwisata Merauke')
                            ->image()
                            ->directory('settings'),
                    ])->columns(2),

                Forms\Components\Section::make('Alamat & Kontak Utama')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap Instansi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('telepon')
                            ->label('Nomor Telepon / WhatsApp')
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Resmi Instansi')
                            ->email(),
                    ])->columns(2),

                Forms\Components\Section::make('Media Sosial Resmi')
                    ->schema([
                        Forms\Components\TextInput::make('instagram')
                            ->label('Tautan Instagram')
                            ->url()
                            ->prefix('IG'),
                        Forms\Components\TextInput::make('facebook')
                            ->label('Tautan Facebook')
                            ->url()
                            ->prefix('FB'),
                        Forms\Components\TextInput::make('youtube')
                            ->label('Tautan YouTube')
                            ->url()
                            ->prefix('YT'),
                        Forms\Components\TextInput::make('tiktok')
                            ->label('Tautan TikTok')
                            ->url()
                            ->prefix('TT'),
                    ])->columns(4),

                Forms\Components\Section::make('Penjelasan Logo Pariwisata (Beranda)')
                    ->schema([
                        Forms\Components\TextInput::make('logo_title')
                            ->label('Judul Bagian Logo')
                            ->placeholder('Contoh: LOGO PARIWISATA MERAUKE')
                            ->default('LOGO PARIWISATA MERAUKE'),

                        Forms\Components\Textarea::make('logo_subtitle')
                            ->label('Subjudul / Deskripsi Singkat')
                            ->rows(2)
                            ->placeholder('Contoh: Tiga ikon di Kabupaten Merauke yaitu Musamus...'),

                        Forms\Components\RichEditor::make('logo_description')
                            ->label('Penjelasan Poin / Makna Logo')
                            ->helperText('Gunakan format Rich Text / Cetak Tebal untuk nomor/poin makna logo.')
                            ->columnSpanFull(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $setting = SiteSetting::first();
        if ($setting) {
            $setting->update($data);
        } else {
            SiteSetting::create($data);
        }

        Notification::make()
            ->success()
            ->title('Tersimpan')
            ->body('Informasi instansi berhasil diperbarui.')
            ->send();
    }
}
