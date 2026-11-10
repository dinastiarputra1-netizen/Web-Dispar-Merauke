<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Restaurant;
use App\Models\Restoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RestaurantDirectoryController extends Controller
{
    public function __invoke(Request $request)
    {
        $search = $request->get('q');
        $jenisFilter = $request->get('jenis');

        if (class_exists(Restaurant::class)) {
            $model = Restaurant::class;
        } elseif (class_exists(Restoran::class)) {
            $model = Restoran::class;
        } else {
            $model = Destination::class;
        }

        $query = $model::query();

        if (Schema::hasColumn($query->getModel()->getTable(), 'is_active')) {
            $query->where('is_active', 1);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if (! empty($jenisFilter)) {
            $query->where('business_type', 'like', "%{$jenisFilter}%");
        }

        $orderColumn = Schema::hasColumn($query->getModel()->getTable(), 'business_name') ? 'business_name' : 'id';
        $allResto = $query->orderBy($orderColumn, 'asc')->get();

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
}
