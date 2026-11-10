@extends('layouts.app')

@section('title', 'Statistik Ekonomi Kreatif | Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke')

@section('content')
<!-- Header Banner Full-Width dengan Warna Background #f1f5f9 -->
<section class="w-full bg-[#f1f5f9] border-b border-slate-200/70 py-8 sm:py-10">
    <div class="w-full px-6 sm:px-12 lg:px-16 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <nav class="flex text-xs text-slate-500 font-medium space-x-2">
                <a href="{{ route('home') }}" class="hover:text-[#0066b2] transition-colors">Beranda</a>
                <span>/</span>
                <span>Data & Analitik</span>
                <span>/</span>
                <span class="text-[#0066b2] font-semibold">Statistik Ekonomi Kreatif</span>
            </nav>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                Statistik Ekonomi Kreatif
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
                Indikator capaian utama, tren perkembangan usaha, dan persebaran subsektor ekonomi kreatif Kabupaten Merauke.
            </p>
        </div>

        <!-- Filter Tahun Dropdown -->
        <form x-data="{ open: false, selected: '{{ $tahun }}' }" x-ref="yearForm" action="{{ route('statistik.ekraf') }}" method="GET" class="shrink-0" @click.outside="open = false">
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Tahun Data</label>
            <input type="hidden" name="tahun" x-model="selected">
            <div class="relative min-w-44">
                <button @click="open = !open" type="button" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 text-xs bg-white border border-slate-200/90 rounded-xl font-medium text-slate-700 shadow-2xs hover:border-[#0066b2] focus:outline-none focus:border-[#0066b2] focus:ring-2 focus:ring-[#0066b2]/10 transition-all" :aria-expanded="open">
                    <span x-text="'Tahun ' + selected"></span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <div x-show="open" x-transition x-cloak class="absolute left-0 right-0 top-full mt-2 z-20 overflow-hidden bg-white border border-slate-200 rounded-xl shadow-xl p-1">
                    @foreach($availableYears as $y)
                        <button @click="selected = '{{ $y }}'; open = false; $nextTick(() => $refs.yearForm.submit())" type="button" class="w-full text-left px-3 py-2 text-xs rounded-lg text-slate-700 hover:bg-slate-100 hover:text-[#0066b2]" :class="selected == '{{ $y }}' && 'bg-blue-50 text-[#0066b2] font-bold'">Tahun {{ $y }}</button>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Main Content Area -->
<section class="w-full bg-[#f8fafc] py-8 sm:py-12 min-h-screen">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-8">

        <!-- 1. KARTU INDIKATOR RINGKASAN TREN (SEKARANG DINAMIS BERDASARKAN TAHUN TERPILIH) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-2xs space-y-2 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-2 h-full bg-[#0066b2]"></div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block">Total Aktivitas ({{ $tahun }})</span>
                <p class="text-2xl sm:text-3xl font-black text-[#0066b2]">
                    {{ number_format($totalSelectedYear, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 font-medium">Transaksi / Produk Terdaftar</p>
            </div>

            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-2xs space-y-2 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-2 h-full bg-emerald-500"></div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block">Puncak Capaian</span>
                <p class="text-2xl sm:text-3xl font-black text-emerald-600">
                    {{ number_format($puncakValue, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 font-medium">{{ $puncakQuarterText }}</p>
            </div>

            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-2xs space-y-2 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-2 h-full bg-amber-500"></div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block">Rata-Rata Triwulan ({{ $tahun }})</span>
                <p class="text-2xl sm:text-3xl font-black text-amber-600">
                    {{ number_format($totalSelectedYear / 4, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 font-medium">Capaian / Triwulan</p>
            </div>

            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-2xs space-y-2 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-2 h-full {{ $growthPercentage >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block">Pertumbuhan YoY</span>
                <p class="text-2xl sm:text-3xl font-black {{ $growthPercentage >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $growthPercentage > 0 ? '+'.$growthPercentage : $growthPercentage }}%
                </p>
                <p class="text-xs text-slate-400 font-medium">Dibandingkan Tahun {{ $tahun - 1 }}</p>
            </div>
        </div>

        <!-- 2. VISUALISASI LINE CHART MULTI-TAHUN PER TRIWULAN -->
        <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6" x-data="{ viewRange: '5' }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-base sm:text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        <span>Grafik Tren Perkembangan Ekonomi Kreatif Per Triwulan</span>
                    </h3>
                    <p class="text-xs text-slate-500 pt-0.5">
                        @if(!empty($yearlyData))
                            Perbandingan pola tren perkembangan sektor ekraf antar tahun ({{ array_key_first($yearlyData) }} - {{ $latestYearInChart }})
                        @else
                            Perbandingan pola tren perkembangan sektor ekraf antar tahun
                        @endif
                    </p>
                </div>

                <!-- Toggle Mode Tampilan Chart -->
                <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/80 shrink-0 self-start sm:self-auto">
                    <button 
                        @click="viewRange = '5'; window.updateEkrafChartFilter('5')" 
                        type="button" 
                        :class="viewRange === '5' ? 'bg-white text-[#0066b2] font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                    >
                        5 Tahun Terakhir
                    </button>
                    <button 
                        @click="viewRange = 'all'; window.updateEkrafChartFilter('all')" 
                        type="button" 
                        :class="viewRange === 'all' ? 'bg-white text-[#0066b2] font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                    >
                        Semua Tahun
                    </button>
                </div>
            </div>

            <!-- Chart Canvas Container -->
            <div class="w-full h-96 sm:h-112.5">
                <canvas id="ekrafLineChart"></canvas>
            </div>
        </div>

        <!-- 3. KARTU INDIKATOR MAKRO & METRIK TERDAFTAR DARI DATABASE -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    <span>Indikator Capaian Terdaftar (Tahun {{ $tahun }})</span>
                </h3>
                <span class="text-xs font-semibold bg-blue-50 text-[#0066b2] px-3 py-1 rounded-full">
                    {{ count($macroIndicators) }} Indikator Terdata
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse($macroIndicators as $item)
                    @php
                        $indikator = $item->indicator_label ?? $item->indicator ?? $item->indikator ?? 'Indikator';
                        $nilai = $item->value ?? $item->nilai ?? 0;
                        $satuan = $item->unit ?? $item->satuan ?? '-';
                        $subSektor = $item->sub_category ?? null;
                        $catatan = $item->notes ?? null;
                    @endphp
                    <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-3 flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">
                                    {{ $indikator }}
                                </span>
                                @if($subSektor)
                                    <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full shrink-0">
                                        {{ $subSektor }}
                                    </span>
                                @else
                                    <span class="bg-blue-50 text-[#0066b2] text-[10px] font-bold px-2.5 py-0.5 rounded-full shrink-0">
                                        Umum
                                    </span>
                                @endif
                            </div>

                            <p class="text-2xl sm:text-3xl font-black text-[#0066b2] tracking-tight">
                                {{ is_numeric($nilai) ? number_format($nilai, 0, ',', '.') : $nilai }}
                                <span class="text-xs font-semibold text-slate-500 tracking-normal ml-0.5">{{ $satuan }}</span>
                            </p>
                        </div>

                        @if($catatan)
                            <p class="text-[11px] text-slate-400 italic pt-2 border-t border-slate-100 line-clamp-2">
                                * {{ $catatan }}
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded-xl border border-slate-200/80 text-center text-slate-500 text-xs sm:text-sm space-y-1">
                        <p class="font-bold text-slate-700">Belum Ada Indikator Spesifik</p>
                        <p>Tidak ada data indikator makro terdaftar yang diinput untuk tahun {{ $tahun }}.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- KARTU DATA KELOMPOK EKRAF DENGAN TABEL RESPONSIVE -->
        <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <h3 class="font-bold text-slate-900 text-lg">Data Kelompok Ekonomi Kreatif ({{ $tahun }})</h3>
                <p class="mt-1 text-sm text-slate-500">Komposisi kelompok berdasarkan kategori OAP dan Non-OAP.</p>
            </div>

            <div class="mx-auto h-64 max-w-xl px-5 py-5 sm:h-80 sm:px-7">
                <canvas id="ekrafGroupDoughnut" aria-label="Grafik jumlah kelompok OAP dan Non-OAP" role="img"></canvas>
            </div>

            <div class="border-t border-slate-100 px-5 py-5 sm:px-7">
                <form action="{{ route('statistik.ekraf') }}" method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <label class="sr-only" for="cari-kelompok-ekraf">Cari kelompok ekonomi kreatif</label>
                    <input
                        id="cari-kelompok-ekraf"
                        type="search"
                        name="cari"
                        value="{{ $groupSearch }}"
                        placeholder="Cari kelompok, ketua, subsektor..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-[#0066b2] focus:outline-none focus:ring-1 focus:ring-[#0066b2] sm:max-w-sm"
                    >
                    <button type="submit" class="rounded-lg bg-[#0066b2] px-5 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#005596]">Cari</button>
                </form>

                <!-- Container Tabel: Border hilang di HP, muncul di layar besar -->
                <div class="overflow-hidden rounded-lg border-0 sm:border sm:border-slate-200" role="region" aria-label="Tabel kelompok ekonomi kreatif" tabindex="0">
                    <table class="w-full border-collapse text-left text-sm">
                        <!-- Header tabel disembunyikan di layar HP -->
                        <thead class="hidden sm:table-header-group bg-sky-100 text-xs font-semibold uppercase text-slate-700">
                            <tr>
                                <th scope="col" class="px-4 py-3.5">Kelompok</th>
                                <th scope="col" class="px-4 py-3.5">Label OAP/Non-OAP</th>
                                <th scope="col" class="px-4 py-3.5">Ketua (Sumber)</th>
                                <th scope="col" class="px-4 py-3.5">Subsektor</th>
                                <th scope="col" class="px-4 py-3.5 whitespace-nowrap">Distrik</th>
                            </tr>
                        </thead>
                        <tbody class="block sm:table-row-group divide-y-0 sm:divide-y divide-slate-100 text-slate-700">
                            @forelse($creativeEconomyGroups as $group)
                                <!-- Baris (tr) diubah menjadi Card (block) pada layar HP -->
                                <tr class="block sm:table-row mb-4 sm:mb-0 bg-white sm:odd:bg-white sm:even:bg-slate-50 border border-slate-200 sm:border-none rounded-xl sm:rounded-none shadow-sm sm:shadow-none overflow-hidden">
                                    
                                    <!-- Sel Data 1 -->
                                    <td class="flex sm:table-cell justify-between items-center sm:items-start px-4 py-3 sm:py-3.5 border-b border-slate-100 sm:border-none">
                                        <span class="sm:hidden text-[11px] font-bold text-slate-500 uppercase">Kelompok</span>
                                        <span class="font-bold sm:font-medium text-slate-900 sm:text-slate-700 text-right sm:text-left">{{ $group->group_name }}</span>
                                    </td>
                                    
                                    <!-- Sel Data 2 -->
                                    <td class="flex sm:table-cell justify-between items-center sm:items-start px-4 py-3 sm:py-3.5 border-b border-slate-100 sm:border-none">
                                        <span class="sm:hidden text-[11px] font-bold text-slate-500 uppercase">Label OAP/Non-OAP</span>
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] sm:text-xs font-semibold {{ $group->classification === 'OAP' ? 'bg-sky-100 text-sky-800' : 'bg-cyan-100 text-cyan-800' }}">{{ $group->classification }}</span>
                                    </td>
                                    
                                    <!-- Sel Data 3 -->
                                    <td class="flex sm:table-cell justify-between items-center sm:items-start px-4 py-3 sm:py-3.5 border-b border-slate-100 sm:border-none">
                                        <span class="sm:hidden text-[11px] font-bold text-slate-500 uppercase">Ketua</span>
                                        <span class="text-right sm:text-left">{{ $group->leader_source ?: '-' }}</span>
                                    </td>
                                    
                                    <!-- Sel Data 4 -->
                                    <td class="flex sm:table-cell justify-between items-center sm:items-start px-4 py-3 sm:py-3.5 border-b border-slate-100 sm:border-none">
                                        <span class="sm:hidden text-[11px] font-bold text-slate-500 uppercase">Subsektor</span>
                                        <span class="text-right sm:text-left">{{ $group->subsector ?: '-' }}</span>
                                    </td>
                                    
                                    <!-- Sel Data 5 -->
                                    <td class="flex sm:table-cell justify-between items-center sm:items-start px-4 py-3 sm:py-3.5">
                                        <span class="sm:hidden text-[11px] font-bold text-slate-500 uppercase">Distrik</span>
                                        <span class="text-right sm:text-left">{{ $group->district ?: '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr class="block sm:table-row bg-white border border-slate-200 sm:border-none rounded-xl">
                                    <td colspan="5" class="block sm:table-cell px-4 py-10 text-center text-slate-500">
                                        {{ $groupSearch ? 'Data kelompok tidak ditemukan.' : 'Belum ada data kelompok untuk tahun ini.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($creativeEconomyGroups->hasPages())
                    <div class="mt-6">{{ $creativeEconomyGroups->links() }}</div>
                @endif
            </div>
        </section>

    </div>
</section>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctxLine = document.getElementById('ekrafLineChart').getContext('2d');
        const labelsQuarter = ['Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV'];
        const rawYearlyData = {!! json_encode($yearlyData) !!};
        const selectedYear = '{{ $tahun }}';

        const colorPalette = [
            '#38bdf8', '#475569', '#22c55e', '#f97316', 
            '#6366f1', '#ec4899', '#eab308', '#06b6d4', '#8b5cf6'
        ];

        const allYears = Object.keys(rawYearlyData);

        function buildEkrafDatasets(filterMode) {
            let selectedYears = allYears;
            if (filterMode === '5' && allYears.length > 5) {
                selectedYears = allYears.slice(-5);
            }

            return selectedYears.map((year, index) => {
                const color = colorPalette[index % colorPalette.length];
                const isSelected = String(year) === String(selectedYear);

                return {
                    label: 'Tahun ' + year,
                    data: rawYearlyData[year],
                    borderColor: color,
                    backgroundColor: color + '1A',
                    borderWidth: isSelected ? 3.5 : 2,
                    tension: 0.3,
                    pointRadius: isSelected ? 5 : 4,
                    pointHoverRadius: 7,
                    pointBackgroundColor: color,
                };
            });
        }

        const ekrafLineChart = new Chart(ctxLine, {
            type: 'line',
            data: { labels: labelsQuarter, datasets: buildEkrafDatasets('5') },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20, font: { size: 12 } }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(v) { return v.toLocaleString('id-ID'); }, font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 12, weight: 'bold' } } }
                }
            }
        });

        window.updateEkrafChartFilter = function(mode) {
            ekrafLineChart.data.datasets = buildEkrafDatasets(mode);
            ekrafLineChart.update();
        };

        const groupChartCanvas = document.getElementById('ekrafGroupDoughnut');
        const groupCounts = @json([(int) ($groupCounts['OAP'] ?? 0), (int) ($groupCounts['Non-OAP'] ?? 0)]);

        new Chart(groupChartCanvas, {
            type: 'doughnut',
            data: {
                labels: ['OAP', 'Non-OAP'],
                datasets: [{
                    data: groupCounts,
                    backgroundColor: ['#1769aa', '#3d98c2'],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18 } },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${context.label}: ${context.raw.toLocaleString('id-ID')} kelompok`,
                        },
                    },
                },
            },
        });
    });
</script>
@endsection