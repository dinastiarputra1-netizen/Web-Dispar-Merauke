<?php

namespace App\Http\Controllers;

use App\Models\MapPoint;

class MapController extends Controller
{
    public function index()
    {
        // 1. Ambil data titik sebaran aktif untuk Peta Leaflet
        $mapPoints = MapPoint::where('is_active', true)->get();

        // 2. Hitung jumlah titik terdaftar per distrik secara dinamis
        $counts = MapPoint::where('is_active', true)
            ->selectRaw('distrik, count(*) as total')
            ->groupBy('distrik')
            ->pluck('total', 'distrik');

        // 3. Daftar Distrik Merauke untuk Ringkasan Sebaran
        $distrikList = collect([
            ['key' => 'Merauke', 'desc' => 'Pusat pemerintahan dan destinasi wisata perkotaan serta pesisir pantai.'],
            ['key' => 'Semangga', 'desc' => 'Kawasan agrowisata dan potensi wisata budaya lokal.'],
            ['key' => 'Tanah Miring', 'desc' => 'Sentra pertanian, edukasi agrowisata, dan potensi kuliner.'],
            ['key' => 'Kurik', 'desc' => 'Kawasan persawahan dan wisata alam perairan.'],
            ['key' => 'Sota', 'desc' => 'Destinasi wisata perbatasan negara RI-PNG dan Taman Nasional Wasur.'],
            ['key' => 'Naukenjerai', 'desc' => 'Kawasan pesisir pantai dan potensi wisata bahari.'],
            ['key' => 'Malind', 'desc' => 'Wilayah pesisir dan budaya masyarakat lokal.'],
            ['key' => 'Okaba', 'desc' => 'Destinasi wisata alam pesisir dan cagar budaya.'],
            ['key' => 'Kimam', 'desc' => 'Kawasan rawa, keanekaragaman hayati, dan wisata petualangan.'],
            ['key' => 'Tubang', 'desc' => 'Potensi wisata sungai dan cagar alam.'],
            ['key' => 'Muting', 'desc' => 'Kawasan perkebunan dan potensi ekowisata hutan.'],
            ['key' => 'Jagebob', 'desc' => 'Kawasan agrowisata dan pemukiman etnis.'],
            ['key' => 'Elikobel', 'desc' => 'Potensi ekowisata darat dan perairan.'],
        ])->map(function ($item, $index) use ($counts) {
            $colors = ['bg-blue-600', 'bg-emerald-600', 'bg-amber-600', 'bg-indigo-600', 'bg-rose-600'];

            return [
                'name' => 'Distrik '.$item['key'],
                'count' => $counts[$item['key']] ?? 0,
                'color' => $colors[$index % count($colors)],
                'desc' => $item['desc'],
            ];
        });

        return view('publikasi.peta-sebaran', compact('mapPoints', 'distrikList'));
    }
}
