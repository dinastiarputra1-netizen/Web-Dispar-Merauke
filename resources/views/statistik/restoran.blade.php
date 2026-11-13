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
                            <th class="py-3.5 px-6 text-center w-44">Tautan & Lokasi</th>
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
                                <td colspan="6" class="py-3 px-6 text-slate-900 text-sm">
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
                                    $restoWebsite = $resto->website_url ?? $resto->website ?? $resto->url_web ?? null;
                                    $restoMaps = $resto->maps_url ?? $resto->google_maps_url ?? $resto->google_maps ?? $resto->maps ?? $resto->link_maps ?? null;
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
                                    <td class="py-3.5 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if(!empty($restoWebsite))
                                                <a href="{{ $restoWebsite }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-semibold bg-blue-50 text-[#0066b2] hover:bg-[#0066b2] hover:text-white transition-colors border border-blue-200" title="Kunjungi Website Resmi">
                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                                </a>
                                            @endif
                                            @if(!empty($restoMaps))
                                                <a href="{{ $restoMaps }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors border border-emerald-200" title="Buka Lokasi Google Maps">
                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5C10.62 11.5 9.5 10.38 9.5 9S10.62 6.5 12 6.5 14.5 7.62 14.5 9 13.38 11.5 12 11.5z"/></svg>
                                                </a>
                                            @endif
                                            @if(empty($restoWebsite) && empty($restoMaps))
                                                <span class="text-slate-400 font-normal">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 px-6 text-slate-400 italic text-xs text-center">Tidak ada data untuk {{ $groupTitle }}.</td></tr>
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
                                $restoWebsite = $resto->website_url ?? $resto->website ?? $resto->url_web ?? null;
                                $restoMaps = $resto->maps_url ?? $resto->google_maps_url ?? $resto->google_maps ?? $resto->maps ?? $resto->link_maps ?? null;
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

                                @if($restoWebsite || $restoMaps)
                                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-end gap-1.5">
                                        @if($restoWebsite)
                                            <a href="{{ $restoWebsite }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-semibold bg-blue-50 text-[#0066b2] border border-blue-100" aria-label="Website">
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                            </a>
                                        @endif
                                        @if($restoMaps)
                                            <a href="{{ $restoMaps }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100" aria-label="Maps">
                                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5C10.62 11.5 9.5 10.38 9.5 9S10.62 6.5 12 6.5 14.5 7.62 14.5 9 13.38 11.5 12 11.5z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                @endif
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
