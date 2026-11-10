@extends('layouts.app')

@section('title', 'Direktori Hotel dan Penginapan - ' . ($siteSetting?->nama_instansi ?? 'Dinas Pariwisata'))

@section('content')

@php
    $siteSetting = $siteSetting ?? \App\Models\SiteSetting::first();
@endphp

<!-- 1. Hero Banner Section -->
<div class="w-full bg-white text-slate-900 py-8 sm:py-10 px-6 sm:px-12 border-b border-slate-200">
    <div class="w-full">
        <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-2">
            <a href="{{ url('/') }}" class="hover:text-[#0066b2] transition-colors">Beranda</a>
            <span>/</span>
            <span>Direktori</span>
            <span>/</span>
            <span class="text-[#0066b2] font-semibold">Hotel & Penginapan</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
            Direktori Hotel & Penginapan
        </h1>
        <p class="mt-1 text-xs sm:text-sm text-slate-600">
            Daftar resmi sarana akomodasi, hotel bintang, penginapan, dan home stay di {{ $siteSetting?->nama_daerah ?? 'Kabupaten Merauke' }}.
        </p>
    </div>
</div>

<!-- 2. Main Section (Fullwidth Container) -->
<section class="w-full py-8 sm:py-12 bg-slate-50/80 min-h-screen px-4 sm:px-6 md:px-12">
    <div class="w-full">
        
        <!-- Table & Card Container -->
        <div class="w-full bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden p-5 sm:p-8">
            
            <!-- Filter & Search Controls Bar -->
            <form action="{{ url('/statistik/akomodasi/hotel-dan-penginapan') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 mb-6">
                
                <!-- Left: Klasifikasi Filter Tabs -->
                <div class="flex flex-wrap items-center gap-2 pb-1 sm:pb-0">
                    <a 
                        href="{{ url('/statistik/akomodasi/hotel-dan-penginapan') }}" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ empty($klasifikasi) ? 'bg-[#0066b2] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Semua
                    </a>
                    <a 
                        href="{{ url('/statistik/akomodasi/hotel-dan-penginapan?klasifikasi=Bintang') }}" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $klasifikasi === 'Bintang' ? 'bg-[#0066b2] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Hotel Bintang
                    </a>
                    <a 
                        href="{{ url('/statistik/akomodasi/hotel-dan-penginapan?klasifikasi=Non Bintang') }}" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $klasifikasi === 'Non Bintang' ? 'bg-[#0066b2] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Non Bintang
                    </a>
                </div>

                <!-- Right: Search Bar -->
                <div class="flex items-center gap-2">
                    <div class="relative flex-1 md:w-72">
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ request('q') }}"
                            placeholder="Cari nama hotel / alamat..." 
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
            <div class="hidden md:block w-full overflow-x-auto rounded-lg border border-slate-200" role="region" aria-label="Direktori hotel dan penginapan" tabindex="0">
                <table class="w-full text-left border-collapse [&_th]:whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#e3f2fd] text-slate-900 text-sm font-bold border-b border-slate-300">
                            <th class="py-3.5 px-4 w-16 text-center">No</th>
                            <th class="py-3.5 px-6">Nama Hotel</th>
                            <th class="py-3.5 px-6">Alamat</th>
                            <th class="py-3.5 px-6">No. Telepon / Kontak</th>
                            <th class="py-3.5 px-6 text-center w-36">Klasifikasi</th>
                            <th class="py-3.5 px-6 text-center w-44">Tautan & Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                        @foreach($groupedHotels as $groupTitle => $items)
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
                            @forelse($items as $index => $hotel)
                                @php
                                    $hotelName = $hotel->nama_usaha ?? $hotel->nama ?? $hotel->name ?? $hotel->title ?? '-';
                                    $hotelAddress = $hotel->alamat ?? $hotel->address ?? '-';
                                    $hotelPhone = $hotel->telepon ?? $hotel->phone ?? $hotel->no_hp ?? $hotel->hp ?? null;
                                    $hotelClass = $hotel->klasifikasi ?? $hotel->classification ?? 'Non Bintang';
                                    $hotelWebsite = $hotel->website_url ?? $hotel->website ?? $hotel->url_web ?? null;
                                    $hotelMaps = $hotel->google_maps_url ?? $hotel->google_maps ?? $hotel->maps_url ?? $hotel->maps ?? $hotel->link_maps ?? null;
                                    $isBintang = str_contains(strtolower($hotelClass), 'bintang') && !str_contains(strtolower($hotelClass), 'non');
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="py-3.5 px-4 text-center font-medium text-slate-600">{{ $index + 1 }}.{{ $groupCode }}</td>
                                    <td class="py-3.5 px-6 font-semibold text-slate-900">{{ $hotelName }}</td>
                                    <td class="py-3.5 px-6 text-slate-600">{{ $hotelAddress }}</td>
                                    <td class="py-3.5 px-6 text-slate-700 font-medium">
                                        @if(!empty($hotelPhone))
                                            @php $cleanPhone = preg_replace('/[^0-9+]/', '', $hotelPhone); @endphp
                                            <a href="tel:{{ $cleanPhone }}" class="inline-flex items-center gap-1.5 text-slate-800 hover:text-[#0066b2] transition-colors">
                                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.47-5.127-3.773-6.597-6.597l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                                <span>{{ $hotelPhone }}</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400 font-normal">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        @if($isBintang)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">★ {{ $hotelClass }}</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">{{ $hotelClass }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if(!empty($hotelWebsite))
                                                <a href="{{ $hotelWebsite }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-semibold bg-blue-50 text-[#0066b2] hover:bg-[#0066b2] hover:text-white transition-colors border border-blue-200" title="Kunjungi Website Resmi"><svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg><span>Web</span></a>
                                            @endif
                                            @if(!empty($hotelMaps))
                                                <a href="{{ $hotelMaps }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors border border-emerald-200" title="Buka Lokasi Google Maps"><svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg><span>Maps</span></a>
                                            @endif
                                            @if(empty($hotelWebsite) && empty($hotelMaps))
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
                @foreach($groupedHotels as $groupTitle => $items)
                    @php $groupCode = substr($groupTitle, 0, 1); @endphp
                    
                    <div class="space-y-3">
                        <!-- Group Header Mobile -->
                        <h2 class="font-black text-slate-900 text-lg border-b-2 border-slate-200 pb-1">
                            {{ $groupTitle }}
                        </h2>

                        @forelse($items as $index => $hotel)
                            @php
                                $hotelName = $hotel->nama_usaha ?? $hotel->nama ?? $hotel->name ?? $hotel->title ?? '-';
                                $hotelAddress = $hotel->alamat ?? $hotel->address ?? '-';
                                $hotelPhone = $hotel->telepon ?? $hotel->phone ?? $hotel->no_hp ?? $hotel->hp ?? null;
                                $hotelClass = $hotel->klasifikasi ?? $hotel->classification ?? 'Non Bintang';
                                $hotelWebsite = $hotel->website_url ?? $hotel->website ?? $hotel->url_web ?? null;
                                $hotelMaps = $hotel->google_maps_url ?? $hotel->google_maps ?? $hotel->maps_url ?? $hotel->maps ?? $hotel->link_maps ?? null;
                                $isBintang = str_contains(strtolower($hotelClass), 'bintang') && !str_contains(strtolower($hotelClass), 'non');
                            @endphp

                            <!-- Card Hotel -->
                            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 relative">
                                <!-- Badge Klasifikasi Top Right -->
                                <div class="absolute top-4 right-4">
                                    @if($isBintang)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">★ {{ $hotelClass }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">{{ $hotelClass }}</span>
                                    @endif
                                </div>

                                <!-- Nama & Index -->
                                <div class="flex items-start gap-2 mb-2 pr-20">
                                    <span class="text-xs font-bold text-[#0066b2] mt-0.5">{{ $index + 1 }}.{{ $groupCode }}</span>
                                    <h3 class="font-bold text-slate-900 leading-tight">{{ $hotelName }}</h3>
                                </div>

                                <!-- Alamat -->
                                <p class="text-xs text-slate-600 mb-3 line-clamp-2">
                                    {{ $hotelAddress }}
                                </p>

                                <!-- Telepon & Actions -->
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                    <div>
                                        @if(!empty($hotelPhone))
                                            @php $cleanPhone = preg_replace('/[^0-9+]/', '', $hotelPhone); @endphp
                                            <a href="tel:{{ $cleanPhone }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 hover:text-[#0066b2]">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.47-5.127-3.773-6.597-6.597l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                                <span>{{ $hotelPhone }}</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">Tanpa Kontak</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex gap-1.5">
                                        @if(!empty($hotelWebsite))
                                            <a href="{{ $hotelWebsite }}" target="_blank" rel="noopener noreferrer" class="p-1.5 rounded-md bg-blue-50 text-[#0066b2] border border-blue-100" aria-label="Website">
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                            </a>
                                        @endif
                                        @if(!empty($hotelMaps))
                                            <a href="{{ $hotelMaps }}" target="_blank" rel="noopener noreferrer" class="p-1.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100" aria-label="Maps">
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                            </a>
                                        @endif
                                    </div>
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
                Menampilkan total {{ $totalData }} entri akomodasi.
            </div>

        </div>

    </div>
</section>

@endsection