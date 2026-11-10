<?php

namespace Database\Seeders;

use App\Models\NusantaraTouristStatistic;
use App\Models\StatisticCategory;
use Illuminate\Database\Seeder;

class NusantaraTouristStatisticSeeder extends Seeder
{
    public function run(): void
    {
        $category = StatisticCategory::where('slug', 'wisatawan-nusantara')->first();

        $data = [
            ['year' => 2017, 'q1' => 11407, 'q2' => 13974, 'q3' => 14510, 'q4' => 13683],
            ['year' => 2018, 'q1' => 1345,  'q2' => 908,   'q3' => 4263,  'q4' => 3382],
            ['year' => 2019, 'q1' => 4500,  'q2' => 4200,  'q3' => 3900,  'q4' => 5800],
            ['year' => 2020, 'q1' => 3200,  'q2' => 500,   'q3' => 600,   'q4' => 700],
            ['year' => 2021, 'q1' => 11800, 'q2' => 15000, 'q3' => 16000, 'q4' => 28714],
            ['year' => 2022, 'q1' => 22924, 'q2' => 31548, 'q3' => 24232, 'q4' => 29714],
            ['year' => 2023, 'q1' => 32604, 'q2' => 36085, 'q3' => 45519, 'q4' => 20192],
        ];

        foreach ($data as $item) {
            NusantaraTouristStatistic::updateOrCreate(
                ['year' => $item['year']],
                array_merge($item, [
                    'statistic_category_id' => $category ? $category->id : null,
                ])
            );
        }
    }
}
