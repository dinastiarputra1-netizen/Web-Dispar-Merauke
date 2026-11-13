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

        <!-- KARTU DATA KELOMPOK EKRAF DENGAN TABEL RESPONSIVE -->
        <section class="overflow-hidden rounded-xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Data Kelompok Ekonomi Kreatif ({{ $tahun }})</h3>
                    <p class="mt-1 text-sm text-slate-500">Komposisi kelompok berdasarkan kategori OAP dan Non-OAP.</p>
                </div>
                
                <!-- Filter Cepat OAP / Non-OAP -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('statistik.ekraf', ['tahun' => $tahun, 'cari' => $groupSearch]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ empty($oapFilter) ? 'bg-[#0066b2] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('statistik.ekraf', ['tahun' => $tahun, 'oap_filter' => 'OAP', 'cari' => $groupSearch]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $oapFilter === 'OAP' ? 'bg-[#0066b2] text-white shadow-sm' : 'bg-sky-100 text-sky-800 hover:bg-sky-200' }}">
                        OAP Saja
                    </a>
                    <a href="{{ route('statistik.ekraf', ['tahun' => $tahun, 'oap_filter' => 'Non-OAP', 'cari' => $groupSearch]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $oapFilter === 'Non-OAP' ? 'bg-[#0066b2] text-white shadow-sm' : 'bg-cyan-100 text-cyan-800 hover:bg-cyan-200' }}">
                        Non-OAP Saja
                    </a>
                </div>
            </div>

            <div class="mx-auto h-64 max-w-xl px-5 py-5 sm:h-80 sm:px-7">
                <canvas id="ekrafGroupDoughnut" aria-label="Grafik jumlah kelompok OAP dan Non-OAP" role="img"></canvas>
            </div>

            <div class="border-t border-slate-100 px-5 py-5 sm:px-7">
                <form action="{{ route('statistik.ekraf') }}" method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    @if($oapFilter)
                        <input type="hidden" name="oap_filter" value="{{ $oapFilter }}">
                    @endif
                    <label class="sr-only" for="cari-kelompok-ekraf">Cari kelompok ekonomi kreatif</label>
                    <input
                        id="cari-kelompok-ekraf"
                        type="search"
                        name="cari"
                        value="{{ $groupSearch }}"
                        placeholder="Cari kelompok, ketua, subsektor..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-[#0066b2] focus:outline-none focus:ring-1 focus:ring-[#0066b2] sm:max-w-sm"
                    >
                    <div class="flex items-center gap-2">
                        <button type="submit" class="rounded-lg bg-[#0066b2] px-5 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#005596]">Cari</button>
                        @if($groupSearch || $oapFilter)
                            <a href="{{ route('statistik.ekraf', ['tahun' => $tahun]) }}" class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300 transition-colors">Reset</a>
                        @endif
                    </div>
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
                        backgroundColor: '#0f172a',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                    }
                }
            }
        });
    });
</script>
@endsection