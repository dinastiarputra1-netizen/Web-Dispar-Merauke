<?php

namespace Database\Seeders;

use App\Models\NationalTouristStatistic;
use App\Models\StatisticCategory;
use Illuminate\Database\Seeder;

class NationalTouristStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $category = StatisticCategory::where('slug', 'wisatawan-nasional')->first();
        $catId = $category ? $category->id : null;

        NationalTouristStatistic::truncate();

        $data = [
            ['year' => 2020, 'q1' => 12500, 'q2' => 8400,  'q3' => 10200, 'q4' => 14500],
            ['year' => 2021, 'q1' => 15100, 'q2' => 18200, 'q3' => 21000, 'q4' => 24800],
            ['year' => 2022, 'q1' => 26500, 'q2' => 31000, 'q3' => 34800, 'q4' => 39200],
            ['year' => 2023, 'q1' => 41200, 'q2' => 48500, 'q3' => 53100, 'q4' => 59800],
            ['year' => 2024, 'q1' => 61500, 'q2' => 69000, 'q3' => 74200, 'q4' => 81000],
            ['year' => 2025, 'q1' => 83500, 'q2' => 91200, 'q3' => 98500, 'q4' => 106000],
            ['year' => 2026, 'q1' => 108000, 'q2' => 116500, 'q3' => 124000, 'q4' => 131200],
        ];

        foreach ($data as $item) {
            NationalTouristStatistic::create(array_merge($item, [
                'statistic_category_id' => $catId,
            ]));
        }
    }
}
