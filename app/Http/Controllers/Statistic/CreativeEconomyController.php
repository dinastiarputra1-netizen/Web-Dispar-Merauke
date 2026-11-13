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
        $oapFilter = trim((string) $request->query('oap_filter', ''));

        if ($oapFilter !== '' && in_array($oapFilter, ['OAP', 'Non-OAP'], true)) {
            $groupQuery->where('classification', $oapFilter);
        }

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

        return view('statistik.ekonomi', compact(
            'creativeEconomyGroups',
            'groupCounts',
            'groupSearch',
            'oapFilter',
            'availableYears',
            'tahun'
        ));
    }
}
