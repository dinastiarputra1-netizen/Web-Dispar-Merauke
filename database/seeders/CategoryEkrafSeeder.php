<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryEkrafSeeder extends Seeder
{
    public function run(): void
    {
        $subsektors = [
            'Kuliner', 'Kriya (Kerajinan)', 'Fesyen & Wastra', 'Seni Pertunjukan',
            'Musik', 'Seni Rupa', 'Fotografi', 'Film, Animasi & Video',
            'Desain Komunikasi Visual', 'Desain Produk', 'Desain Interior',
            'Arsitektur', 'Penerbitan', 'Periklanan', 'Televisi & Radio',
            'Pengembang Permainan (Game)', 'Aplikasi Digital',
        ];

        foreach ($subsektors as $item) {
            Category::firstOrCreate([
                'slug' => Str::slug($item),
            ], [
                'name' => $item,
                'type' => 'ekraf',
            ]);
        }
    }
}
