<?php

namespace Database\Seeders;

use App\Models\StatisticCategory;
use App\Models\TourismForeignExchangeStatistic;
use Illuminate\Database\Seeder;

class TourismForeignExchangeStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = StatisticCategory::where('slug', 'devisa-pariwisata')->value('id');

        TourismForeignExchangeStatistic::query()->delete();

        foreach ([
            ['year' => 2020, 'q1' => 1200000000, 'q2' => 1350000000, 'q3' => 1500000000, 'q4' => 1650000000],
            ['year' => 2021, 'q1' => 1800000000, 'q2' => 1950000000, 'q3' => 2100000000, 'q4' => 2250000000],
            ['year' => 2022, 'q1' => 2400000000, 'q2' => 2650000000, 'q3' => 2800000000, 'q4' => 3100000000],
            ['year' => 2023, 'q1' => 3300000000, 'q2' => 3550000000, 'q3' => 3800000000, 'q4' => 4100000000],
            ['year' => 2024, 'q1' => 4350000000, 'q2' => 4700000000, 'q3' => 5050000000, 'q4' => 5450000000],
            ['year' => 2025, 'q1' => 5800000000, 'q2' => 6250000000, 'q3' => 6700000000, 'q4' => 7200000000],
            ['year' => 2026, 'q1' => 7600000000, 'q2' => 8150000000, 'q3' => 8750000000, 'q4' => 9400000000],
        ] as $data) {
            TourismForeignExchangeStatistic::create(array_merge($data, [
                'statistic_category_id' => $categoryId,
            ]));
        }
    }
}
