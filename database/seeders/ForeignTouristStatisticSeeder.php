<?php

namespace Database\Seeders;

use App\Models\ForeignTouristStatistic;
use App\Models\StatisticCategory;
use Illuminate\Database\Seeder;

class ForeignTouristStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $category = StatisticCategory::where('slug', 'wisatawan-mancanegara')->first();
        $catId = $category ? $category->id : null;

        ForeignTouristStatistic::truncate();

        $data = [
            ['year' => 2020, 'q1' => 120, 'q2' => 15,  'q3' => 25,  'q4' => 40],
            ['year' => 2021, 'q1' => 30,  'q2' => 45,  'q3' => 80,  'q4' => 110],
            ['year' => 2022, 'q1' => 140, 'q2' => 210, 'q3' => 320, 'q4' => 280],
            ['year' => 2023, 'q1' => 310, 'q2' => 450, 'q3' => 520, 'q4' => 490],
            ['year' => 2024, 'q1' => 510, 'q2' => 680, 'q3' => 890, 'q4' => 750],
            ['year' => 2025, 'q1' => 820, 'q2' => 950, 'q3' => 1150, 'q4' => 1020],
            ['year' => 2026, 'q1' => 980, 'q2' => 1120, 'q3' => 1350, 'q4' => 1210],
        ];

        foreach ($data as $item) {
            ForeignTouristStatistic::create(array_merge($item, [
                'statistic_category_id' => $catId,
            ]));
        }
    }
}
