<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HotelStatisticController extends Controller
{
    public function __invoke(Request $request)
    {
        $search = $request->get('q');
        $klasifikasi = $request->get('klasifikasi');

        $hotelModel = class_exists(Hotel::class) ? Hotel::class : null;

        if ($hotelModel && Schema::hasTable('hotels')) {
            $columns = Schema::getColumnListing('hotels');
            $query = $hotelModel::query();

            $nameCol = in_array('nama_usaha', $columns) ? 'nama_usaha' : (in_array('nama', $columns) ? 'nama' : (in_array('name', $columns) ? 'name' : 'title'));
            $addressCol = in_array('alamat', $columns) ? 'alamat' : (in_array('address', $columns) ? 'address' : null);
            $typeCol = in_array('jenis', $columns) ? 'jenis' : (in_array('type', $columns) ? 'type' : (in_array('kategori', $columns) ? 'kategori' : null));
            $classCol = in_array('klasifikasi', $columns) ? 'klasifikasi' : (in_array('classification', $columns) ? 'classification' : null);

            if (in_array('is_active', $columns)) {
                $query->where('is_active', true);
            } elseif (in_array('status', $columns)) {
                $query->where(function ($q) {
                    $q->where('status', 'active')->orWhere('status', 'published')->orWhere('status', 1);
                });
            }

            if (! empty($search)) {
                $query->where(function ($q) use ($search, $nameCol, $addressCol) {
                    $q->where($nameCol, 'like', "%{$search}%");
                    if ($addressCol) {
                        $q->orWhere($addressCol, 'like', "%{$search}%");
                    }
                });
            }

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
}
