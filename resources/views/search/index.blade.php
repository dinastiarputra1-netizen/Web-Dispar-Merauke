@extends('layouts.app')

@section('title', 'Pencarian | Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke')

@section('content')
<!-- Header Hero & Search Bar -->
<section class="w-full bg-[#f1f5f9] border-b border-slate-200/70 py-10 sm:py-16">
    <div class="w-full px-6 sm:px-12 lg:px-16 text-center space-y-4">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Pusat Pencarian</h1>
        <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Temukan informasi wisata, agenda, akomodasi, kuliner, dan publikasi di Kabupaten Merauke dengan cepat dan mudah.
        </p>
        
        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="w-full max-w-4xl mx-auto mt-8">
            <div class="flex flex-col sm:flex-row items-stretch gap-3 sm:gap-4 w-full">
                
                <!-- Input Box -->
                <div class="flex-1 flex items-center bg-white border border-slate-300 rounded-2xl shadow-sm focus-within:ring-4 focus-within:ring-[#0066b2]/15 focus-within:border-[#0066b2] transition-all overflow-hidden">
                    <div class="pl-5 pr-3 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </div>
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $search }}" 
                        placeholder="Ketik kata kunci pencarian..." 
                        autofocus 
                        class="w-full py-4 px-2 text-base text-slate-900 bg-transparent border-none outline-none focus:ring-0 placeholder-slate-400"
                    >
                </div>

                <!-- Tombol Cari (Ditambahkan shrink-0 agar tidak gepeng) -->
                <button type="submit" class="w-full sm:w-auto shrink-0 px-10 py-4 bg-[#0066b2] text-white text-base font-bold rounded-2xl hover:bg-[#004d86] shadow-md transition-all flex items-center justify-center">
                    Cari
                </button>
                
            </div>
        </form>
    </div>
</section>

<!-- Main Content Area -->
<section class="w-full bg-[#f8fafc] py-10 sm:py-16 min-h-screen">
    <div class="w-full px-6 sm:px-12 lg:px-16">
    
    @if($search === '')
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 sm:p-20 text-center max-w-4xl mx-auto">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6 text-[#0066b2]">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672ZM12 2.25V4.5m5.834.166-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-5.834 1.59-1.59"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Mulai Pencarian Anda</h3>
            <p class="text-sm text-slate-500 max-w-md">Silakan masukkan kata kunci pada kolom pencarian di atas untuk menemukan informasi destinasi, berita, atau layanan terkait.</p>
        </div>

    @elseif($results->isEmpty())
        <!-- Not Found State -->
        <div class="flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 sm:p-20 text-center max-w-4xl mx-auto">
            <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mb-6 text-rose-500">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Pencarian Tidak Ditemukan</h3>
            <p class="text-sm text-slate-500 max-w-md mb-6">Maaf, kami tidak menemukan hasil apapun untuk kata kunci <span class="font-bold text-slate-900">"{{ $search }}"</span>. Coba gunakan istilah yang lebih umum.</p>
            <a href="{{ route('search') }}" class="text-sm font-semibold text-[#0066b2] hover:underline">Clear Pencarian</a>
        </div>

    @else
        <!-- Results Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200/80 pb-4 mb-8 gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Hasil Pencarian</h2>
                <p class="text-sm text-slate-500 mt-1">Menampilkan hasil untuk <span class="font-bold text-slate-800">"{{ $search }}"</span></p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-[#0066b2] text-xs font-bold border border-blue-100 shadow-2xs">
                {{ $results->count() }} Data Ditemukan
            </span>
        </div>

        <!-- Results Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($results as $result)
            <a href="{{ $result['url'] }}" class="group flex flex-col bg-white border border-slate-200/80 rounded-2xl overflow-hidden hover:border-[#0066b2] hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                
                <!-- Image Cover -->
                <div class="relative aspect-video overflow-hidden bg-slate-100">
                    <img 
                        src="{{ $result['image'] ?? asset('images/logodisparmerauke.webp') }}" 
                        alt="{{ $result['title'] }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        onerror="this.src='{{ asset('images/logodisparmerauke.webp') }}'"
                    >
                    <div class="absolute top-4 left-4">
                        <span class="inline-block px-3.5 py-1.5 bg-[#0066b2] text-white text-[10px] uppercase tracking-wider font-extrabold rounded-lg shadow-md">
                            {{ $result['type'] }}
                        </span>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-[#0066b2] transition-colors leading-tight mb-3 line-clamp-2">
                        {{ $result['title'] }}
                    </h3>
                    
                    @if($result['description'])
                    <p class="text-sm text-slate-500 line-clamp-2 mb-6 grow italic">
                        {{ $result['description'] }}
                    </p>
                    @endif

                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between text-sm font-bold text-[#0066b2]">
                        <span>Selengkapnya</span>
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-[#0066b2] group-hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
    </div>
</section>
@endsection