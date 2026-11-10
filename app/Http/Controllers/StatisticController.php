<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\NusantaraTouristStatistic;
use App\Models\Restaurant;
use App\Models\Restoran;
use App\Models\StatisticCategory;
use App\Models\StatisticEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StatisticController extends Controller
{
    /**
     * Halaman Statistik Ekonomi Kreatif
     */
    public function ekraf(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // 1. Ambil Kategori 'ekonomi-kreatif' dari StatisticCategory
        $category = StatisticCategory::where('slug', 'ekonomi-kreatif')->first();

        // 2. Query Indikator Makro dari StatisticEntry dengan Pengecekan Kolom Aman
        $query = StatisticEntry::query();

        if ($category && Schema::hasColumn('statistic_entries', 'statistic_category_id')) {
            $query->where('statistic_category_id', $category->id);
        } else {
            $query->where(function ($q) {
                if (Schema::hasColumn('statistic_entries', 'kategori')) {
                    $q->orWhere('kategori', 'like', '%Ekonomi Kreatif%');
                }
                if (Schema::hasColumn('statistic_entries', 'category')) {
                    $q->orWhere('category', 'like', '%Ekonomi Kreatif%');
                }
            });
        }

        // Filter Tahun Aman
        if (Schema::hasColumn('statistic_entries', 'year')) {
            $query->where('year', $tahun);
        } elseif (Schema::hasColumn('statistic_entries', 'tahun')) {
            $query->where('tahun', $tahun);
        }

        $macroIndicators = $query->get();

        // Opsi Tahun untuk Filter Dropdown Header
        $yearCol = Schema::hasColumn('statistic_entries', 'year') ? 'year' : (Schema::hasColumn('statistic_entries', 'tahun') ? 'tahun' : null);

        $availableYears = $yearCol
            ? StatisticEntry::pluck($yearCol)->filter()->unique()->sortDesc()
            : collect([date('Y')]);

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        // 3. Data Line Chart Tren Multi-Tahun (Per Triwulan) Ekonomi Kreatif
        $yearlyData = [
            '2018' => [1250, 1420, 1680, 1850],
            '2019' => [1920, 2100, 2350, 2600],
            '2020' => [1800, 1200, 1500, 1950],
            '2021' => [2200, 2650, 3100, 3800],
            '2022' => [4100, 4850, 5200, 5900],
            '2023' => [6200, 7150, 8400, 7800],
            '2024' => [8100, 9300, 11200, 10500],
        ];

        $latestYear = array_key_last($yearlyData);
        $latestQuarterData = $yearlyData[$latestYear];
        $totalLatestYear = array_sum($latestQuarterData);

        // Hitung Puncak & YoY Growth
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

        // 4. Data Subsektor Ekraf dari Listing Usaha (Destination)
        $totalUsaha = Destination::where('type', 'ekraf')
            ->where('status', 'published')
            ->count();

        $categories = Category::where('type', 'ekraf')
            ->withCount(['destinations' => function ($q) {
                $q->where('type', 'ekraf')->where('status', 'published');
            }])
            ->get();

        $subsectorStats = [];
        foreach ($categories as $cat) {
            $count = $cat->destinations_count;
            $share = $totalUsaha > 0 ? round(($count / $totalUsaha) * 100, 1).'%' : '0%';

            $subsectorStats[] = [
                'name' => $cat->name,
                'count' => $count,
                'labor' => $count * 4,
                'share' => $share,
            ];
        }

        return view('statistik.ekonomi', compact(
            'macroIndicators',
            'availableYears',
            'tahun',
            'totalUsaha',
            'subsectorStats',
            'yearlyData',
            'latestYear',
            'totalLatestYear',
            'puncakValue',
            'puncakQuarterText',
            'growthPercentage'
        ));
    }

    /**
     * Halaman Statistik Hotel dan Akomodasi Penginapan
     */
    public function hotelPenginapan(Request $request)
    {
        $search = $request->get('q');
        $klasifikasi = $request->get('klasifikasi');

        // 1. Cek ketersediaan Model Hotel dan Tabel 'hotels'
        $hotelModel = class_exists(Hotel::class) ? Hotel::class : null;

        if ($hotelModel && Schema::hasTable('hotels')) {
            $columns = Schema::getColumnListing('hotels');
            $query = $hotelModel::query();

            // Deteksi nama kolom secara otomatis berdasarkan database
            $nameCol = in_array('nama_usaha', $columns) ? 'nama_usaha' : (in_array('nama', $columns) ? 'nama' : (in_array('name', $columns) ? 'name' : 'title'));
            $addressCol = in_array('alamat', $columns) ? 'alamat' : (in_array('address', $columns) ? 'address' : null);
            $typeCol = in_array('jenis', $columns) ? 'jenis' : (in_array('type', $columns) ? 'type' : (in_array('kategori', $columns) ? 'kategori' : null));
            $classCol = in_array('klasifikasi', $columns) ? 'klasifikasi' : (in_array('classification', $columns) ? 'classification' : null);

            // Filter status aktif
            if (in_array('is_active', $columns)) {
                $query->where('is_active', true);
            } elseif (in_array('status', $columns)) {
                $query->where(function ($q) {
                    $q->where('status', 'active')->orWhere('status', 'published')->orWhere('status', 1);
                });
            }

            // Filter Pencarian
            if (! empty($search)) {
                $query->where(function ($q) use ($search, $nameCol, $addressCol) {
                    $q->where($nameCol, 'like', "%{$search}%");
                    if ($addressCol) {
                        $q->orWhere($addressCol, 'like', "%{$search}%");
                    }
                });
            }

            // Filter Klasifikasi (Bintang vs Non Bintang)
            if (! empty($klasifikasi) && $classCol) {
                if ($klasifikasi === 'Bintang') {
                    $query->where($classCol, 'like', '%Bintang%')->where($classCol, 'not like', '%Non%');
                } elseif ($klasifikasi === 'Non Bintang') {
                    $query->where(function ($q) use ($classCol) {
                        $q->where($classCol, 'like', '%Non%')->orWhere($classCol, 'not like', '%Bintang%');
                    });
                }
            }

            $allHotels = $query->orderBy($nameCol, 'asc')->get();
        } else {
            $allHotels = collect();
        }

        // 2. Pengelompokan Data ke 3 Kategori Utama (A. Hotel, B. Penginapan, C. Home Stay)
        $groupedHotels = [
            'A. Hotel' => collect(),
            'B. Penginapan' => collect(),
            'C. Home Stay' => collect(),
        ];

        foreach ($allHotels as $item) {
            $jenisVal = strtolower(
                ($item->jenis ?? $item->type ?? $item->kategori ?? $item->nama_usaha ?? $item->name ?? '')
            );

            if (str_contains($jenisVal, 'home') || str_contains($jenisVal, 'homestay')) {
                $groupedHotels['C. Home Stay']->push($item);
            } elseif (str_contains($jenisVal, 'penginapan') || str_contains($jenisVal, 'losmen') || str_contains($jenisVal, 'wisma')) {
                $groupedHotels['B. Penginapan']->push($item);
            } else {
                $groupedHotels['A. Hotel']->push($item);
            }
        }

        return view('statistik.hotel-dan-penginapan', compact('groupedHotels', 'search', 'klasifikasi'));
    }

    /**
     * Halaman Direktori Restoran dan Kuliner
     */
    public function restoran(Request $request)
    {
        $search = $request->get('q');
        $jenisFilter = $request->get('jenis');

        // 1. Inisialisasi Model Restoran
        if (class_exists(Restaurant::class)) {
            $model = Restaurant::class;
        } elseif (class_exists(Restoran::class)) {
            $model = Restoran::class;
        } else {
            $model = Destination::class;
        }

        $query = $model::query();

        // Filter Status Aktif (is_active = 1)
        if (Schema::hasColumn($query->getModel()->getTable(), 'is_active')) {
            $query->where('is_active', 1);
        }

        // Filter Pencarian Global
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        // Filter Jenis Usaha (Tab Navigasi)
        if (! empty($jenisFilter)) {
            $query->where('business_type', 'like', "%{$jenisFilter}%");
        }

        // Sorting berdasarkan Nama Kegiatan Usaha
        $orderColumn = Schema::hasColumn($query->getModel()->getTable(), 'business_name') ? 'business_name' : 'id';
        $allResto = $query->orderBy($orderColumn, 'asc')->get();

        // 2. Pengelompokan Data berdasarkan business_type
        $groupedResto = [
            'A. Restaurant / Rumah Makan' => collect(),
            'B. Cafe & Kedai Kopi' => collect(),
            'C. Jasa Boga' => collect(),
            'D. Bar, Karaoke & Kelab Malam' => collect(),
        ];

        foreach ($allResto as $item) {
            $type = strtolower($item->business_type ?? $item->jenis_usaha ?? '');

            if (str_contains($type, 'cafe') || str_contains($type, 'kafe') || str_contains($type, 'kopi')) {
                $groupedResto['B. Cafe & Kedai Kopi']->push($item);
            } elseif (str_contains($type, 'jasa boga') || str_contains($type, 'boga') || str_contains($type, 'catering')) {
                $groupedResto['C. Jasa Boga']->push($item);
            } elseif (str_contains($type, 'bar') || str_contains($type, 'karaoke') || str_contains($type, 'kelab') || str_contains($type, 'malam')) {
                $groupedResto['D. Bar, Karaoke & Kelab Malam']->push($item);
            } else {
                $groupedResto['A. Restaurant / Rumah Makan']->push($item);
            }
        }

        return view('statistik.restoran', compact('groupedResto', 'search', 'jenisFilter'));
    }

    /**
     * Halaman Statistik Wisatawan Nusantara (Dinamis via Relasi Kategori)
     */
    public function wisatawanNusantara(Request $request)
    {
        // Query melalui relasi StatisticCategory 'wisatawan-nusantara'
        $category = StatisticCategory::where('slug', 'wisatawan-nusantara')->first();

        $statistics = $category
            ? $category->nusantaraTouristStatistics()->orderBy('year', 'asc')->get()
            : NusantaraTouristStatistic::orderBy('year', 'asc')->get();

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

            // Hitung Puncak Kunjungan di Tahun Terakhir
            $maxQuarterVal = max($latestQuarterData);
            $maxQuarterIndex = array_search($maxQuarterVal, $latestQuarterData);
            $quarterNames = ['Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV'];

            $puncakValue = $maxQuarterVal;
            $puncakQuarterText = $quarterNames[$maxQuarterIndex]." (Tahun {$latestYear})";

            // Hitung Pertumbuhan YoY
            $years = array_keys($yearlyData);
            $prevYear = count($years) >= 2 ? $years[count($years) - 2] : null;

            if ($prevYear && array_sum($yearlyData[$prevYear]) > 0) {
                $totalPrevYear = array_sum($yearlyData[$prevYear]);
                $growthPercentage = number_format((($totalLatestYear - $totalPrevYear) / $totalPrevYear) * 100, 1);
            } else {
                $growthPercentage = 0;
            }
        }

        return view('statistik.wisatawan-nusantara', compact(
            'yearlyData',
            'latestYear',
            'totalLatestYear',
            'puncakValue',
            'puncakQuarterText',
            'growthPercentage'
        ));
    }
}
