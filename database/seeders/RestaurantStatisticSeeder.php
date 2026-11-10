<?php

namespace Database\Seeders;

use App\Models\RestaurantStatistic;
use App\Models\StatisticCategory;
use Illuminate\Database\Seeder;

class RestaurantStatisticSeeder extends Seeder
{
    public function run(): void
    {
        // Mencari kategori berdasarkan slug yang ada di database: 'usaha-restoran-kuliner'
        $category = StatisticCategory::where('slug', 'usaha-restoran-kuliner')->first();
        $catId = $category ? $category->id : null;

        RestaurantStatistic::truncate();

        $data = [
            ['year' => 2020, 'q1' => 1200, 'q2' => 950,  'q3' => 1100, 'q4' => 1400],
            ['year' => 2021, 'q1' => 1550, 'q2' => 1800, 'q3' => 2100, 'q4' => 2450],
            ['year' => 2022, 'q1' => 2680, 'q2' => 3100, 'q3' => 3450, 'q4' => 3900],
            ['year' => 2023, 'q1' => 4150, 'q2' => 4800, 'q3' => 5300, 'q4' => 5950],
            ['year' => 2024, 'q1' => 6200, 'q2' => 6950, 'q3' => 7400, 'q4' => 8150],
            ['year' => 2025, 'q1' => 8400, 'q2' => 9200, 'q3' => 9900, 'q4' => 10700],
            ['year' => 2026, 'q1' => 10900, 'q2' => 11700, 'q3' => 12500, 'q4' => 13200],
        ];

        foreach ($data as $item) {
            RestaurantStatistic::create(array_merge($item, [
                'statistic_category_id' => $catId,
            ]));
        }
    }
}
