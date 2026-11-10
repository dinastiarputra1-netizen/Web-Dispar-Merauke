@extends('layouts.app')

@section('title', 'Statistik Jasa Perjalanan | Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke')

@section('content')
<!-- Header Banner Full-Width -->
<section class="w-full bg-[#f1f5f9] border-b border-slate-200/70 py-8 sm:py-10">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-2">
        <nav class="flex text-xs text-slate-500 font-medium space-x-2">
            <a href="{{ route('home') }}" class="hover:text-[#0066b2] transition-colors">Beranda</a>
            <span>/</span>
            <span>Data & Analitik</span>
            <span>/</span>
            <span class="text-[#0066b2] font-semibold">Jasa Perjalanan</span>
        </nav>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Statistik Jasa Perjalanan
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">
            Data tren aktivitas jasa perjalanan dan layanan penunjang pariwisata (Travel, Biro Perjalanan, Pramuwisata, Transportasi) di Kabupaten Merauke 
            @if(!empty($yearlyData))
                periode tahun {{ array_key_first($yearlyData) }} hingga {{ $latestYear }}.
            @else
                Kabupaten Merauke.
            @endif
        </p>
    </div>
</section>

<!-- Main Content Area -->
<section class="w-full bg-[#f8fafc] py-8 sm:py-12 min-h-screen">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-8">

        <!-- Ringkasan Indikator Aktivitas Jasa Perjalanan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-2xs space-y-2">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block">Total Aktivitas ({{ $latestYear }})</span>
                <p class="text-2xl sm:text-3xl font-black text-[#0066b2]">
                    {{ number_format($totalLatestYear, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 font-medium">Layanan Jasa Perjalanan Terdaftar</p>
            </div>

            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-2xs space-y-2">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block">Puncak Aktivitas</span>
                <p class="text-2xl sm:text-3xl font-black text-emerald-600">
                    {{ number_format($puncakValue, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 font-medium">{{ $puncakQuarterText }}</p>
            </div>

            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-2xs space-y-2">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block">Rata-Rata Triwulan ({{ $latestYear }})</span>
                <p class="text-2xl sm:text-3xl font-black text-amber-600">
                    {{ number_format($totalLatestYear / 4, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 font-medium">Aktivitas / Triwulan</p>
            </div>

            <div class="bg-white p-5 sm:p-6 rounded-xl border border-slate-200/80 shadow-2xs space-y-2">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide block">Pertumbuhan YoY</span>
                <p class="text-2xl sm:text-3xl font-black {{ $growthPercentage >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $growthPercentage > 0 ? '+'.$growthPercentage : $growthPercentage }}%
                </p>
                <p class="text-xs text-slate-400 font-medium">Dibandingkan Tahun Sebelumnya</p>
            </div>
        </div>

        <!-- Visualisasi Line Chart -->
        <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6" x-data="{ viewRange: '5' }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-base sm:text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m0 0V5.25A2.25 2.25 0 0110.5 3h3a2.25 2.25 0 012.25 2.25v1.094m-7.5 0c1.233-.066 2.476-.1 3.75-.1s2.517.034 3.75.1" />
                        </svg>
                        <span>Grafik Perbandingan Aktivitas Jasa Perjalanan Per Triwulan</span>
                    </h3>
                    <p class="text-xs text-slate-500 pt-0.5">
                        @if(!empty($yearlyData))
                            Perbandingan pola tren aktivitas antar tahun ({{ array_key_first($yearlyData) }} - {{ $latestYear }})
                        @else
                            Perbandingan pola tren aktivitas antar tahun
                        @endif
                    </p>
                </div>

                <!-- Toggle Mode Tampilan Chart -->
                <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/80 shrink-0 self-start sm:self-auto">
                    <button 
                        @click="viewRange = '5'; window.updateJasaUsahaChartFilter('5')" 
                        type="button" 
                        :class="viewRange === '5' ? 'bg-white text-[#0066b2] font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all cursor-pointer"
                    >
                        5 Tahun Terakhir
                    </button>
                    <button 
                        @click="viewRange = 'all'; window.updateJasaUsahaChartFilter('all')" 
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
                <canvas id="jasaUsahaLineChart"></canvas>
            </div>
        </div>

        <!-- Bagian Data Komprehensif (Two-Views Method) -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-4 sm:p-6 space-y-4">
            <h3 class="font-bold text-slate-900 text-base sm:text-lg border-b border-slate-100 pb-3">
                Data Angka Aktivitas Jasa Perjalanan 
                @if(!empty($yearlyData))
                    ({{ array_key_first($yearlyData) }} - {{ $latestYear }})
                @endif
            </h3>

            <!-- 1. TABEL KHUSUS DESKTOP (Tersembunyi di HP) -->
            <div class="hidden sm:block overflow-x-auto rounded-lg border border-slate-200">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 whitespace-nowrap">Tahun</th>
                            <th class="py-3.5 px-4 whitespace-nowrap text-center">Triwulan I</th>
                            <th class="py-3.5 px-4 whitespace-nowrap text-center">Triwulan II</th>
                            <th class="py-3.5 px-4 whitespace-nowrap text-center">Triwulan III</th>
                            <th class="py-3.5 px-4 whitespace-nowrap text-center">Triwulan IV</th>
                            <th class="py-3.5 px-4 whitespace-nowrap text-center font-extrabold text-[#0066b2]">Total Tahunan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($yearlyData as $year => $values)
                            @php $rowTotal = array_sum($values); @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-900">Tahun {{ $year }}</td>
                                <td class="py-3.5 px-4 text-center">{{ number_format($values[0], 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4 text-center">{{ number_format($values[1], 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4 text-center">{{ number_format($values[2], 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4 text-center">{{ number_format($values[3], 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4 text-center font-bold text-[#0066b2] bg-blue-50/40">
                                    {{ number_format($rowTotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-4 text-center text-slate-500">
                                    Belum ada data statistik jasa perjalanan yang diinput.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 2. KARTU KHUSUS MOBILE (Tersembunyi di Desktop) -->
            <div class="sm:hidden space-y-4">
                @forelse($yearlyData as $year => $values)
                    @php $rowTotal = array_sum($values); @endphp
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                        <div class="py-3 px-4 font-bold text-slate-900 border-b border-slate-100 bg-slate-50">
                            Tahun {{ $year }}
                        </div>
                        
                        <div class="flex justify-between items-center py-2.5 px-4 border-b border-slate-50">
                            <span class="text-xs font-semibold text-slate-500">Triwulan I</span>
                            <span class="text-sm font-medium text-slate-700">{{ number_format($values[0], 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2.5 px-4 border-b border-slate-50">
                            <span class="text-xs font-semibold text-slate-500">Triwulan II</span>
                            <span class="text-sm font-medium text-slate-700">{{ number_format($values[1], 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2.5 px-4 border-b border-slate-50">
                            <span class="text-xs font-semibold text-slate-500">Triwulan III</span>
                            <span class="text-sm font-medium text-slate-700">{{ number_format($values[2], 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2.5 px-4 border-b border-slate-100">
                            <span class="text-xs font-semibold text-slate-500">Triwulan IV</span>
                            <span class="text-sm font-medium text-slate-700">{{ number_format($values[3], 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-3.5 px-4 font-bold text-[#0066b2] bg-blue-50/40">
                            <span class="text-[11px] font-extrabold uppercase text-[#0066b2]">Total Tahunan</span>
                            <span class="text-sm">{{ number_format($rowTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 px-4 text-center text-sm text-slate-500 border border-slate-200 rounded-xl bg-white shadow-sm">
                        Belum ada data statistik jasa perjalanan yang diinput.
                    </div>
                @endforelse
            </div>

        </div>

    </div>
</section>

<!-- Chart.js Script Dinamis -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('jasaUsahaLineChart').getContext('2d');
        const labels = ['Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV'];
        const rawData = {!! json_encode($yearlyData) !!};
        const latestYear = '{{ $latestYear }}';

        const colorPalette = [
            '#38bdf8', '#475569', '#22c55e', '#f97316', 
            '#6366f1', '#ec4899', '#eab308', '#06b6d4', '#8b5cf6'
        ];

        const allYears = Object.keys(rawData);

        function buildDatasets(filterMode) {
            let selectedYears = allYears;
            if (filterMode === '5' && allYears.length > 5) {
                selectedYears = allYears.slice(-5);
            }

            return selectedYears.map((year, index) => {
                const color = colorPalette[index % colorPalette.length];
                const isLatest = String(year) === String(latestYear);

                return {
                    label: 'Tahun ' + year,
                    data: rawData[year],
                    borderColor: color,
                    backgroundColor: color + '1A',
                    borderWidth: isLatest ? 3.5 : 2,
                    tension: 0.3,
                    pointRadius: isLatest ? 5 : 4,
                    pointHoverRadius: 7,
                    pointBackgroundColor: color,
                };
            });
        }

        const chartInstance = new Chart(ctx, {
            type: 'line',
            data: { labels: labels, datasets: buildDatasets('5') },
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
                        ticks: { callback: function(value) { return value.toLocaleString('id-ID'); }, font: { size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 12, weight: 'bold' } } }
                }
            }
        });

        window.updateJasaUsahaChartFilter = function(mode) {
            chartInstance.data.datasets = buildDatasets(mode);
            chartInstance.update();
        };
    });
</script>
@endsection