<?php

namespace Database\Seeders;

use App\Models\StatisticCategory;
use App\Models\TourismServiceStatistic;
use Illuminate\Database\Seeder;

class TourismServiceStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $category = StatisticCategory::where('slug', 'jasa-perjalanan-wisata')->first();
        $catId = $category ? $category->id : null;

        TourismServiceStatistic::truncate();

        $data = [
            ['year' => 2020, 'q1' => 420,  'q2' => 310,  'q3' => 380,  'q4' => 490],
            ['year' => 2021, 'q1' => 550,  'q2' => 620,  'q3' => 710,  'q4' => 840],
            ['year' => 2022, 'q1' => 920,  'q2' => 1050, 'q3' => 1200, 'q4' => 1380],
            ['year' => 2023, 'q1' => 1450, 'q2' => 1620, 'q3' => 1850, 'q4' => 1980],
            ['year' => 2024, 'q1' => 2100, 'q2' => 2350, 'q3' => 2680, 'q4' => 2900],
            ['year' => 2025, 'q1' => 3120, 'q2' => 3450, 'q3' => 3800, 'q4' => 4150],
            ['year' => 2026, 'q1' => 4300, 'q2' => 4750, 'q3' => 5200, 'q4' => 5600],
        ];

        foreach ($data as $item) {
            TourismServiceStatistic::create(array_merge($item, [
                'statistic_category_id' => $catId,
            ]));
        }
    }
}
