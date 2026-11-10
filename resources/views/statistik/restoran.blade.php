@extends('layouts.app')

@section('title', 'Direktori Restoran dan Kuliner - ' . ($siteSetting?->nama_instansi ?? 'Dinas Pariwisata'))

@section('content')

@php
    $siteSetting = $siteSetting ?? \App\Models\SiteSetting::first();
@endphp

<!-- Hero Banner Section -->
<div class="w-full bg-white text-slate-900 py-8 sm:py-10 px-6 sm:px-12 border-b border-slate-200">
    <div class="w-full">
        <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-2">
            <a href="{{ url('/') }}" class="hover:text-[#0066b2] transition-colors">Beranda</a>
            <span>/</span>
            <span>Direktori</span>
            <span>/</span>
            <span class="text-[#0066b2] font-semibold">Restoran & Kuliner</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
            Direktori Restoran & Rumah Makan
        </h1>
        <p class="mt-1 text-xs sm:text-sm text-slate-600">
            Daftar resmi sarana usaha kuliner, restoran, rumah makan, cafe, dan hiburan malam di {{ $siteSetting?->nama_daerah ?? 'Kabupaten Merauke' }}.
        </p>
    </div>
</div>

<!-- Main Section -->
<section class="w-full py-8 sm:py-12 bg-slate-50/80 min-h-screen px-4 sm:px-6 md:px-12">
    <div class="w-full">
        
        <!-- Table & Card Container -->
        <div class="w-full bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden p-5 sm:p-8">
            
            <!-- Controls Bar: Filter & Search Form -->
            <form action="{{ url('/statistik/akomodasi/restoran') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 mb-6">
                
                <!-- Left: Jenis Filter Tabs -->
                <div class="flex flex-wrap items-center gap-2 pb-1 sm:pb-0">
                    <a 
                        href="{{ url('/statistik/akomodasi/restoran') }}" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ empty($jenisFilter) ? 'bg-[#0066b2] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Semua Kuliner
                    </a>
                    <a 
                        href="{{ url('/statistik/akomodasi/restoran?jenis=Restaurant') }}" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $jenisFilter === 'Restaurant' ? 'bg-[#0066b2] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Restoran / Rumah Makan
                    </a>
                    <a 
                        href="{{ url('/statistik/akomodasi/restoran?jenis=Cafe') }}" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $jenisFilter === 'Cafe' ? 'bg-[#0066b2] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Cafe
                    </a>
                    <a 
                        href="{{ url('/statistik/akomodasi/restoran?jenis=Jasa Boga') }}" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $jenisFilter === 'Jasa Boga' ? 'bg-[#0066b2] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Jasa Boga
                    </a>
                    <a 
                        href="{{ url('/statistik/akomodasi/restoran?jenis=Bar') }}" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $jenisFilter === 'Bar' ? 'bg-[#0066b2] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Bar & Hiburan
                    </a>
                </div>

                <!-- Right: Search Bar -->
                <div class="flex items-center gap-2">
                    <div class="relative flex-1 md:w-72">
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ request('q') }}"
                            placeholder="Cari nama usaha / izin / alamat..." 
                            class="w-full pl-9 pr-4 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-[#0066b2] focus:ring-1 focus:ring-[#0066b2] text-slate-800 placeholder-slate-400"
                        />
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </div>
                    <button 
                        type="submit" 
                        class="bg-[#0066b2] hover:bg-[#005596] text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-xs"
                    >
                        Cari
                    </button>
                </div>
            </form>

            @php $totalData = 0; @endphp

            <!-- ============================================== -->
            <!-- 1. TAMPILAN TABEL KHUSUS DESKTOP (MD & UP) -->
            <!-- ============================================== -->
            <div class="hidden md:block w-full overflow-x-auto rounded-lg border border-slate-200" role="region" aria-label="Direktori restoran dan kuliner" tabindex="0">
                <table class="w-full text-left border-collapse [&_th]:whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#e3f2fd] text-slate-900 text-sm font-bold border-b border-slate-300">
                            <th class="py-3.5 px-4 w-16 text-center">No</th>
                            <th class="py-3.5 px-6">Nama Kegiatan Usaha</th>
                            <th class="py-3.5 px-6 text-center w-52">Jenis Usaha</th>
                            <th class="py-3.5 px-6 w-56">No. Izin Usaha</th>
                            <th class="py-3.5 px-6">Alamat Lengkap</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                        @foreach($groupedResto as $groupTitle => $items)
                            @php 
                                $groupCode = substr($groupTitle, 0, 1);
                                $totalData += $items->count();
                            @endphp

                            <!-- Sub-Header Kategori -->
                            <tr class="bg-slate-100 font-bold text-slate-900 border-t border-b border-slate-300">
                                <td colspan="5" class="py-3 px-6 text-slate-900 text-sm">
                                    {{ $groupTitle }}
                                </td>
                            </tr>

                            <!-- Baris Data Desktop -->
                            @forelse($items as $index => $resto)
                                @php
                                    $restoName = $resto->business_name ?? $resto->nama_kegiatan_usaha ?? '-';
                                    $restoJenis = $resto->business_type ?? $resto->jenis_usaha ?? '-';
                                    $restoIzin = $resto->license_number ?? $resto->no_izin_usaha ?? '-';
                                    $restoAddress = $resto->address ?? $resto->alamat_lengkap ?? '-';
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="py-3.5 px-4 text-center font-medium text-slate-600">{{ $index + 1 }}.{{ $groupCode }}</td>
                                    <td class="py-3.5 px-6 font-semibold text-slate-900">{{ $restoName }}</td>
                                    <td class="py-3.5 px-6 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $restoJenis }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6 font-mono text-slate-700 text-xs">{{ $restoIzin }}</td>
                                    <td class="py-3.5 px-6 text-slate-600">{{ $restoAddress }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 px-6 text-slate-400 italic text-xs text-center">Tidak ada data untuk {{ $groupTitle }}.</td></tr>
                            @endforelse
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- ============================================== -->
            <!-- 2. TAMPILAN KARTU KHUSUS MOBILE (SM & BAWAH) -->
            <!-- ============================================== -->
            <div class="md:hidden space-y-6">
                @foreach($groupedResto as $groupTitle => $items)
                    @php $groupCode = substr($groupTitle, 0, 1); @endphp
                    
                    <div class="space-y-3">
                        <!-- Group Header Mobile -->
                        <h2 class="font-black text-slate-900 text-lg border-b-2 border-slate-200 pb-1">
                            {{ $groupTitle }}
                        </h2>

                        @forelse($items as $index => $resto)
                            @php
                                $restoName = $resto->business_name ?? $resto->nama_kegiatan_usaha ?? '-';
                                $restoJenis = $resto->business_type ?? $resto->jenis_usaha ?? '-';
                                $restoIzin = $resto->license_number ?? $resto->no_izin_usaha ?? '-';
                                $restoAddress = $resto->address ?? $resto->alamat_lengkap ?? '-';
                            @endphp

                            <!-- Card Resto -->
                            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
                                
                                <!-- Badge Jenis Usaha (Sekarang di atas, bukan absolute) -->
                                <div class="mb-2.5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $restoJenis }}
                                    </span>
                                </div>

                                <!-- Nama Kegiatan Usaha & Index -->
                                <div class="flex items-start gap-2 mb-3">
                                    <span class="text-xs font-bold text-[#0066b2] mt-0.5 shrink-0">{{ $index + 1 }}.{{ $groupCode }}</span>
                                    <h3 class="font-bold text-slate-900 leading-tight">{{ $restoName }}</h3>
                                </div>

                                <!-- Alamat Lengkap -->
                                <div class="mb-3 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                    <p class="text-xs text-slate-600 line-clamp-2">
                                        {{ $restoAddress }}
                                    </p>
                                </div>
                                
                                <!-- No. Izin Usaha -->
                                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider shrink-0">No. Izin Usaha:</span>
                                    <span class="font-mono text-slate-700 text-[11px] sm:text-xs bg-slate-50 px-2 py-1 rounded border border-slate-100 text-right truncate">{{ $restoIzin }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-slate-400 italic text-xs text-center border border-dashed border-slate-300 rounded-lg">
                                Tidak ada data untuk {{ $groupTitle }}.
                            </div>
                        @endforelse
                    </div>
                @endforeach
            </div>

            <!-- Footer Info -->
            <div class="mt-4 sm:mt-6 text-xs text-slate-500 font-medium">
                Menampilkan total {{ $totalData }} entri usaha kuliner.
            </div>

        </div>

    </div>
</section>

@endsection