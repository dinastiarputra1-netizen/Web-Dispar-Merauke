<?php

namespace Database\Seeders;

use App\Models\StatisticCategory;
use App\Models\TourismSatelliteStatistic;
use Illuminate\Database\Seeder;

class TourismSatelliteStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = StatisticCategory::where('slug', 'satelit-pariwisata')->value('id');

        TourismSatelliteStatistic::query()->delete();

        foreach ([
            ['year' => 2020, 'q1' => 120, 'q2' => 135, 'q3' => 150, 'q4' => 165],
            ['year' => 2021, 'q1' => 180, 'q2' => 195, 'q3' => 210, 'q4' => 225],
            ['year' => 2022, 'q1' => 240, 'q2' => 265, 'q3' => 280, 'q4' => 310],
            ['year' => 2023, 'q1' => 330, 'q2' => 355, 'q3' => 380, 'q4' => 410],
            ['year' => 2024, 'q1' => 435, 'q2' => 470, 'q3' => 505, 'q4' => 545],
            ['year' => 2025, 'q1' => 580, 'q2' => 625, 'q3' => 670, 'q4' => 720],
            ['year' => 2026, 'q1' => 760, 'q2' => 815, 'q3' => 875, 'q4' => 940],
        ] as $data) {
            TourismSatelliteStatistic::create(array_merge($data, [
                'statistic_category_id' => $categoryId,
            ]));
        }
    }
}
