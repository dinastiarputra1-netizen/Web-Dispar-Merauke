<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\CreativeEconomyGroup;
use App\Models\CreativeEconomyStatistic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CreativeEconomyController extends Controller
{
    public function __invoke(Request $request)
    {
        // 1. Opsi Pilihan Tahun dari Database
        $availableYears = CreativeEconomyStatistic::query()
            ->distinct()
            ->pluck('year')
            ->merge(CreativeEconomyGroup::query()->distinct()->pluck('year'))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        // Tahun terpilih dari dropdown filter header (Default: Tahun Terbaru di Database)
        $tahun = (int) $request->get('tahun', $availableYears->first());

        // 2. Indikator Makro / Capaian Terdaftar untuk Tahun Terpilih
        $macroIndicators = CreativeEconomyStatistic::where('year', $tahun)->get();
        $groupCounts = CreativeEconomyGroup::query()
            ->where('year', $tahun)
            ->selectRaw('classification, COUNT(*) as total')
            ->groupBy('classification')
            ->pluck('total', 'classification');
        $groupQuery = CreativeEconomyGroup::query()->where('year', $tahun);
        $groupSearch = trim((string) $request->query('cari', ''));

        if ($groupSearch !== '') {
            $groupQuery->where(function (Builder $query) use ($groupSearch): void {
                $query->where('group_name', 'like', "%{$groupSearch}%")
                    ->orWhere('classification', 'like', "%{$groupSearch}%")
                    ->orWhere('leader_source', 'like', "%{$groupSearch}%")
                    ->orWhere('subsector', 'like', "%{$groupSearch}%")
                    ->orWhere('district', 'like', "%{$groupSearch}%");
            });
        }

        $creativeEconomyGroups = $groupQuery
            ->orderBy('group_name')
            ->paginate(10)
            ->withQueryString();

        // 3. Data Line Chart Tren Pertriwulan Multi-Tahun
        $rawTrendData = CreativeEconomyStatistic::selectRaw('
                year,
                SUM(CASE WHEN month IN (1,2,3) THEN value ELSE 0 END) as q1,
                SUM(CASE WHEN month IN (4,5,6) THEN value ELSE 0 END) as q2,
                SUM(CASE WHEN month IN (7,8,9) THEN value ELSE 0 END) as q3,
                SUM(CASE WHEN month IN (10,11,12) THEN value ELSE 0 END) as q4,
                SUM(value) as total_val
            ')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $yearlyData = [];
        foreach ($rawTrendData as $row) {
            if ($row->q1 == 0 && $row->q2 == 0 && $row->q3 == 0 && $row->q4 == 0 && $row->total_val > 0) {
                $portion = round($row->total_val / 4, 2);
                $yearlyData[$row->year] = [$portion, $portion, $portion, $portion];
            } else {
                $yearlyData[$row->year] = [(float) $row->q1, (float) $row->q2, (float) $row->q3, (float) $row->q4];
            }
        }

        // 4. Kalkulasi Kartu Ringkasan Indikator Tren Berdasarkan TAHUN TERPILIH ($tahun)
        $selectedYearData = $yearlyData[$tahun] ?? [0, 0, 0, 0];
        $totalSelectedYear = array_sum($selectedYearData);

        $maxQuarterVal = max($selectedYearData);
        $maxQuarterIndex = array_search($maxQuarterVal, $selectedYearData);
        $quarterNames = ['Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV'];

        $puncakValue = $maxQuarterVal;
        $puncakQuarterText = $maxQuarterVal > 0
            ? $quarterNames[$maxQuarterIndex]." (Tahun {$tahun})"
            : '-';

        // Hitung Pertumbuhan YoY (Tahun Terpilih vs Tahun Sebelumnya)
        $prevYear = $tahun - 1;
        if (isset($yearlyData[$prevYear]) && array_sum($yearlyData[$prevYear]) > 0) {
            $totalPrevYear = array_sum($yearlyData[$prevYear]);
            $growthPercentage = number_format((($totalSelectedYear - $totalPrevYear) / $totalPrevYear) * 100, 1);
        } else {
            $growthPercentage = 0;
        }

        $latestYearInChart = ! empty($yearlyData) ? array_key_last($yearlyData) : date('Y');

        return view('statistik.ekonomi', compact(
            'macroIndicators',
            'creativeEconomyGroups',
            'groupCounts',
            'groupSearch',
            'availableYears',
            'tahun',
            'yearlyData',
            'totalSelectedYear',
            'puncakValue',
            'puncakQuarterText',
            'growthPercentage',
            'latestYearInChart'
        ));
    }
}
