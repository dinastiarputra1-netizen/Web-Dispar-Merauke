<?php

namespace Database\Seeders;

use App\Models\CreativeEconomyStatistic;
use App\Models\StatisticCategory;
use Illuminate\Database\Seeder;

class CreativeEconomyStatisticSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil atau tentukan ID Kategori Ekonomi Kreatif
        $category = StatisticCategory::where('slug', 'ekonomi-kreatif')->first();
        $categoryId = $category ? $category->id : 9;

        // Reset data lama kategori ekonomi kreatif agar bersih saat dipanggil ulang
        CreativeEconomyStatistic::where('statistic_category_id', $categoryId)->delete();

        // 2. Data Historis 5 Tahun (2022 - 2026)
        $data = [
            // --- TAHUN 2022 ---
            ['year' => 2022, 'month' => 2,  'sub_category' => 'Kuliner',          'indicator_label' => 'Total Transaksi Triwulan I',   'value' => 4100000000.00, 'unit' => 'Rupiah', 'notes' => 'Aktivitas kuliner lokal Q1'],
            ['year' => 2022, 'month' => 5,  'sub_category' => 'Kriya',            'indicator_label' => 'Total Transaksi Triwulan II',  'value' => 4850000000.00, 'unit' => 'Rupiah', 'notes' => 'Aktivitas kerajinan tangan Q2'],
            ['year' => 2022, 'month' => 8,  'sub_category' => 'Fesyen',           'indicator_label' => 'Total Transaksi Triwulan III', 'value' => 5200000000.00, 'unit' => 'Rupiah', 'notes' => 'Pameran fesyen daerah Q3'],
            ['year' => 2022, 'month' => 11, 'sub_category' => 'Seni Pertunjukan', 'indicator_label' => 'Total Transaksi Triwulan IV',  'value' => 5900000000.00, 'unit' => 'Rupiah', 'notes' => 'Festival seni akhir tahun Q4'],
            ['year' => 2022, 'month' => null, 'sub_category' => 'Umum',           'indicator_label' => 'Tenaga Kerja Ekraf',           'value' => 1250.00,       'unit' => 'Orang',  'notes' => 'Total tenaga kerja terdaftar 2022'],

            // --- TAHUN 2023 ---
            ['year' => 2023, 'month' => 2,  'sub_category' => 'Kuliner',          'indicator_label' => 'Total Transaksi Triwulan I',   'value' => 6200000000.00, 'unit' => 'Rupiah', 'notes' => 'Capaian transaksi Q1'],
            ['year' => 2023, 'month' => 5,  'sub_category' => 'Musik & Fotografi', 'indicator_label' => 'Total Transaksi Triwulan II',  'value' => 7150000000.00, 'unit' => 'Rupiah', 'notes' => 'Event musik daerah Q2'],
            ['year' => 2023, 'month' => 8,  'sub_category' => 'Kriya & Fesyen',   'indicator_label' => 'Total Transaksi Triwulan III', 'value' => 8400000000.00, 'unit' => 'Rupiah', 'notes' => 'Puncak transaksi bazar UMKM Q3'],
            ['year' => 2023, 'month' => 11, 'sub_category' => 'Aplikasi & Game',  'indicator_label' => 'Total Transaksi Triwulan IV',  'value' => 7800000000.00, 'unit' => 'Rupiah', 'notes' => 'Capaian produk digital Q4'],
            ['year' => 2023, 'month' => null, 'sub_category' => 'Umum',           'indicator_label' => 'Unit Usaha Terdaftar',         'value' => 480.00,        'unit' => 'Unit',   'notes' => 'Jumlah usaha terdata 2023'],

            // --- TAHUN 2024 ---
            ['year' => 2024, 'month' => 2,  'sub_category' => 'Kuliner Khas',     'indicator_label' => 'Total Transaksi Triwulan I',   'value' => 8100000000.00, 'unit' => 'Rupiah', 'notes' => 'Aktivitas kuliner Q1'],
            ['year' => 2024, 'month' => 5,  'sub_category' => 'Kriya & Souvenir', 'indicator_label' => 'Total Transaksi Triwulan II',  'value' => 9300000000.00, 'unit' => 'Rupiah', 'notes' => 'Aktivitas kriya Q2'],
            ['year' => 2024, 'month' => 8,  'sub_category' => 'Fesyen & Musik',   'indicator_label' => 'Total Transaksi Triwulan III', 'value' => 11200000000.00, 'unit' => 'Rupiah', 'notes' => 'Puncak pameran ekraf Q3'],
            ['year' => 2024, 'month' => 11, 'sub_category' => 'Seni & Pertunjukan', 'indicator_label' => 'Total Transaksi Triwulan IV',  'value' => 10500000000.00, 'unit' => 'Rupiah', 'notes' => 'Event kebudayaan Q4'],
            ['year' => 2024, 'month' => null, 'sub_category' => 'Kuliner',        'indicator_label' => 'Pendapatan Omset Tahunan',     'value' => 39100000000.00, 'unit' => 'Rupiah', 'notes' => 'Total akumulasi omset 2024'],

            // --- TAHUN 2025 ---
            ['year' => 2025, 'month' => 2,  'sub_category' => 'Kuliner',          'indicator_label' => 'Total Transaksi Triwulan I',   'value' => 10200000000.00, 'unit' => 'Rupiah', 'notes' => 'Aktivitas Q1 2025'],
            ['year' => 2025, 'month' => 5,  'sub_category' => 'Kriya',            'indicator_label' => 'Total Transaksi Triwulan II',  'value' => 11800000000.00, 'unit' => 'Rupiah', 'notes' => 'Aktivitas Q2 2025'],
            ['year' => 2025, 'month' => 8,  'sub_category' => 'Fesyen',           'indicator_label' => 'Total Transaksi Triwulan III', 'value' => 13500000000.00, 'unit' => 'Rupiah', 'notes' => 'Puncak event ekraf Q3 2025'],
            ['year' => 2025, 'month' => 11, 'sub_category' => 'Digital & Game',   'indicator_label' => 'Total Transaksi Triwulan IV',  'value' => 12900000000.00, 'unit' => 'Rupiah', 'notes' => 'Capaian Q4 2025'],
            ['year' => 2025, 'month' => null, 'sub_category' => 'Umum',           'indicator_label' => 'Produk HKI Terdaftar',         'value' => 85.00,         'unit' => 'Sertifikat', 'notes' => 'Hak kekayaan intelektual terfasilitasi'],

            // --- TAHUN 2026 ---
            ['year' => 2026, 'month' => 2,  'sub_category' => 'Kuliner Khas',     'indicator_label' => 'Total Transaksi Triwulan I',   'value' => 12500000000.00, 'unit' => 'Rupiah', 'notes' => 'Capaian awal tahun 2026'],
            ['year' => 2026, 'month' => 5,  'sub_category' => 'Kriya Lokal',      'indicator_label' => 'Total Transaksi Triwulan II',  'value' => 14100000000.00, 'unit' => 'Rupiah', 'notes' => 'Capaian pertengahan tahun 2026'],
            ['year' => 2026, 'month' => 8,  'sub_category' => 'Fesyen Batik',     'indicator_label' => 'Total Transaksi Triwulan III', 'value' => 15800000000.00, 'unit' => 'Rupiah', 'notes' => 'Proyeksi puncak Q3 2026'],
            ['year' => 2026, 'month' => 11, 'sub_category' => 'Seni Kreatif',     'indicator_label' => 'Total Transaksi Triwulan IV',  'value' => 14900000000.00, 'unit' => 'Rupiah', 'notes' => 'Proyeksi Q4 2026'],
            ['year' => 2026, 'month' => null, 'sub_category' => 'Kuliner Khas',   'indicator_label' => 'Pendapatan Omset',             'value' => 100000000.00,  'unit' => 'Rupiah', 'notes' => 'Omzet sampel usaha terdaftar'],
            ['year' => 2026, 'month' => null, 'sub_category' => 'Umum',           'indicator_label' => 'Wisatawan Mancanegara',        'value' => 100000.00,     'unit' => 'Orang',  'notes' => 'Kunjungan wisatawan pendukung ekraf'],
        ];

        // 3. Insert Batch Data ke Database
        foreach ($data as $item) {
            CreativeEconomyStatistic::create(array_merge($item, [
                'statistic_category_id' => $categoryId,
            ]));
        }
    }
}
