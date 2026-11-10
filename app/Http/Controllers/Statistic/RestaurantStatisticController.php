<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\RestaurantStatistic;
use App\Models\StatisticCategory;
use Illuminate\Http\Request;

class RestaurantStatisticController extends Controller
{
    public function __invoke(Request $request)
    {
        // Menyesuaikan query agar mencakup slug 'usaha-restoran-kuliner' sesuai database
        $category = StatisticCategory::where('slug', 'usaha-restoran-kuliner')
            ->orWhere('slug', 'usaha-restoran')
            ->orWhere('slug', 'restoran')
            ->first();

        $statistics = $category
            ? $category->restaurantStatistics()->orderBy('year', 'asc')->get()
            : RestaurantStatistic::orderBy('year', 'asc')->get();

        $yearlyData = [];
        foreach ($statistics as $stat) {
            $yearlyData[$stat->year] = [$stat->q1, $stat->q2, $stat->q3, $stat->q4];
        }

        if (empty($yearlyData)) {
            $latestYear = date('Y');
            $totalLatestYear = 0;
            $puncakValue = 0;
            $puncakQuarterText = '-';
            $growthPercentage = 0;
        } else {
            $latestYear = array_key_last($yearlyData);
            $latestQuarterData = $yearlyData[$latestYear];
            $totalLatestYear = array_sum($latestQuarterData);

            $maxQuarterVal = max($latestQuarterData);
            $maxQuarterIndex = array_search($maxQuarterVal, $latestQuarterData);
            $quarterNames = ['Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV'];

            $puncakValue = $maxQuarterVal;
            $puncakQuarterText = $quarterNames[$maxQuarterIndex]." (Tahun {$latestYear})";

            $years = array_keys($yearlyData);
            $prevYear = count($years) >= 2 ? $years[count($years) - 2] : null;

            if ($prevYear && array_sum($yearlyData[$prevYear]) > 0) {
                $totalPrevYear = array_sum($yearlyData[$prevYear]);
                $growthPercentage = number_format((($totalLatestYear - $totalPrevYear) / $totalPrevYear) * 100, 1);
            } else {
                $growthPercentage = 0;
            }
        }

        return view('statistik.usaha-restoran', compact(
            'yearlyData',
            'latestYear',
            'totalLatestYear',
            'puncakValue',
            'puncakQuarterText',
            'growthPercentage'
        ));
    }
}
