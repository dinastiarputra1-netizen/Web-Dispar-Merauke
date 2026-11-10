<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\StatisticCategory;
use App\Models\TourismForeignExchangeStatistic;
use Illuminate\Http\Request;

class TourismForeignExchangeController extends Controller
{
    public function __invoke(Request $request)
    {
        $category = StatisticCategory::where('slug', 'devisa-pariwisata')->first();
        $statistics = $category
            ? $category->tourismForeignExchangeStatistics()->orderBy('year')->get()
            : TourismForeignExchangeStatistic::orderBy('year')->get();

        $yearlyData = [];
        foreach ($statistics as $statistic) {
            $yearlyData[$statistic->year] = [$statistic->q1, $statistic->q2, $statistic->q3, $statistic->q4];
        }

        $latestYear = empty($yearlyData) ? date('Y') : array_key_last($yearlyData);
        $latestQuarterData = $yearlyData[$latestYear] ?? [0, 0, 0, 0];
        $totalLatestYear = array_sum($latestQuarterData);
        $puncakValue = max($latestQuarterData);
        $maxQuarterIndex = array_search($puncakValue, $latestQuarterData);
        $quarterNames = ['Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV'];
        $puncakQuarterText = $puncakValue > 0 ? $quarterNames[$maxQuarterIndex]." (Tahun {$latestYear})" : '-';

        $years = array_keys($yearlyData);
        $previousYear = count($years) >= 2 ? $years[count($years) - 2] : null;
        $totalPreviousYear = $previousYear ? array_sum($yearlyData[$previousYear]) : 0;
        $growthPercentage = $totalPreviousYear > 0
            ? number_format((($totalLatestYear - $totalPreviousYear) / $totalPreviousYear) * 100, 1)
            : 0;

        return view('statistik.devisa-pariwisata', compact(
            'yearlyData', 'latestYear', 'totalLatestYear', 'puncakValue',
            'puncakQuarterText', 'growthPercentage'
        ));
    }
}
