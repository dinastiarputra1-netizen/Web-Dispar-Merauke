<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Page;
use App\Models\StatisticCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kategori Wisata
        $wisataCategories = ['Wisata Alam', 'Wisata Budaya', 'Wisata Sejarah', 'Wisata Bahari', 'Wisata Buatan'];
        foreach ($wisataCategories as $name) {
            Category::firstOrCreate([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'wisata',
            ]);
        }

        // 2. Kategori Ekonomi Kreatif
        $ekrafCategories = ['Kuliner Khas', 'Kerajinan Tangan', 'Fesyen & Kriya', 'Seni Pertunjukan'];
        foreach ($ekrafCategories as $name) {
            Category::firstOrCreate([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => 'ekraf',
            ]);
        }

        // 3. Kategori Agenda Dinas
        Category::firstOrCreate([
            'name' => 'Informasi Agenda Dinas',
            'slug' => 'informasi-agenda-dinas',
            'type' => 'agenda',
        ]);

        // 4. Kategori Statistik Pariwisata
        $statCategories = [
            'Wisatawan Nusantara',
            'Wisatawan Mancanegara',
            'Wisatawan Nasional',
            'Akomodasi Hotel & Penginapan',
            'Usaha Restoran & Kuliner',
            'Jasa Perjalanan Wisata',
            'Satelit Pariwisata',
            'Devisa Pariwisata',
            'Ekonomi Kreatif',
        ];

        foreach ($statCategories as $statName) {
            StatisticCategory::firstOrCreate([
                'name' => $statName,
                'slug' => Str::slug($statName),
            ]);
        }

        // 5. Halaman Statis Awal
        Page::firstOrCreate(
            ['slug' => 'profil-lembaga'],
            ['title' => 'Profil Lembaga', 'content' => '<p>Selamat datang di Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke.</p>']
        );
        Page::firstOrCreate(
            ['slug' => 'struktur-organisasi'],
            ['title' => 'Struktur Organisasi', 'content' => null]
        );
    }
}
