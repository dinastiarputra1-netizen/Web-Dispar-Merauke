@extends('layouts.app')

@section('title', 'Ragam Ekonomi Kreatif | Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke')

@section('content')
<!-- Header Banner Full-Width dengan Warna Background #f1f5f9 -->
<section class="w-full bg-[#f1f5f9] border-b border-slate-200/70 py-8 sm:py-10">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-2">
        <nav class="flex text-xs text-slate-500 font-medium space-x-2">
            <a href="{{ route('home') }}" class="hover:text-[#0066b2] transition-colors">Beranda</a>
            <span>/</span>
            <span>Katalog</span>
            <span>/</span>
            <span class="text-[#0066b2] font-semibold">Ragam Ekonomi Kreatif</span>
        </nav>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Ragam Ekonomi Kreatif
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">
            Jelajahi produk unggulan, kerajinan tangan, kriya, kuliner khas, dan subsektor ekonomi kreatif khas Kabupaten Merauke.
        </p>
    </div>
</section>

<!-- Main Content Area -->
<section class="w-full bg-[#f8fafc] py-8 sm:py-12 min-h-screen">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-8">

        <!-- Filter & Search Bar Custom UI (Tanpa Box Pembungkus) -->
        <form action="{{ url('/publikasi/ragam-ekonomi-kreatif') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-center">
            
            <!-- Search Input dengan Heroicon Magnifying Glass -->
            <div class="md:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Cari produk, usaha, atau lokasi..." 
                    class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-slate-200/90 rounded-xl focus:outline-none focus:border-[#0066b2] focus:ring-2 focus:ring-[#0066b2]/10 transition-all text-slate-800 placeholder-slate-400 font-medium shadow-2xs hover:border-slate-300"
                />
            </div>

            <!-- Custom Category Dropdown (Alpine.js) -->
            <div 
                x-data="{ 
                    open: false, 
                    selected: '{{ request('kategori') }}', 
                    label: '{{ request('kategori') ? ($categories->firstWhere('slug', request('kategori'))->name ?? 'Semua Kategori') : 'Semua Kategori' }}' 
                }" 
                class="relative md:col-span-3"
            >
                <input type="hidden" name="kategori" :value="selected">
                <button 
                    @click="open = !open" 
                    @click.outside="open = false" 
                    type="button" 
                    class="w-full flex items-center justify-between pl-4 pr-3.5 py-2.5 text-xs bg-white border border-slate-200/90 rounded-xl font-medium text-slate-700 shadow-2xs hover:border-slate-300 transition-all focus:outline-none focus:ring-2 focus:ring-[#0066b2]/10"
                >
                    <span x-text="label" class="truncate"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <!-- Menu List Melengkung Halus -->
                <div 
                    x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-slate-200/90 rounded-2xl shadow-xl overflow-hidden py-1.5 text-xs font-medium max-h-60 overflow-y-auto"
                    style="display: none;"
                >
                    <div 
                        @click="selected = ''; label = 'Semua Kategori'; open = false; $nextTick(() => $el.closest('form').submit())" 
                        class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors text-slate-600 hover:text-[#0066b2]"
                        :class="{ 'bg-blue-50/70 text-[#0066b2] font-semibold': selected === '' }"
                    >
                        Semua Kategori
                    </div>
                    @foreach($categories as $cat)
                        <div 
                            @click="selected = '{{ $cat->slug }}'; label = '{{ $cat->name }}'; open = false; $nextTick(() => $el.closest('form').submit())" 
                            class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors text-slate-600 hover:text-[#0066b2]"
                            :class="{ 'bg-blue-50/70 text-[#0066b2] font-semibold': selected === '{{ $cat->slug }}' }"
                        >
                            {{ $cat->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Custom District Dropdown (Alpine.js) -->
            <div 
                x-data="{ 
                    open: false, 
                    selected: '{{ request('distrik') }}', 
                    label: '{{ request('distrik') ?: 'Semua Distrik' }}' 
                }" 
                class="relative md:col-span-3"
            >
                <input type="hidden" name="distrik" :value="selected">
                <button 
                    @click="open = !open" 
                    @click.outside="open = false" 
                    type="button" 
                    class="w-full flex items-center justify-between pl-4 pr-3.5 py-2.5 text-xs bg-white border border-slate-200/90 rounded-xl font-medium text-slate-700 shadow-2xs hover:border-slate-300 transition-all focus:outline-none focus:ring-2 focus:ring-[#0066b2]/10"
                >
                    <span x-text="label" class="truncate"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <!-- Menu List Melengkung Halus -->
                <div 
                    x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-slate-200/90 rounded-2xl shadow-xl overflow-hidden py-1.5 text-xs font-medium max-h-60 overflow-y-auto"
                    style="display: none;"
                >
                    <div 
                        @click="selected = ''; label = 'Semua Distrik'; open = false; $nextTick(() => $el.closest('form').submit())" 
                        class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors text-slate-600 hover:text-[#0066b2]"
                        :class="{ 'bg-blue-50/70 text-[#0066b2] font-semibold': selected === '' }"
                    >
                        Semua Distrik
                    </div>
                    @foreach($districts as $dist)
                        <div 
                            @click="selected = '{{ $dist }}'; label = '{{ $dist }}'; open = false; $nextTick(() => $el.closest('form').submit())" 
                            class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors text-slate-600 hover:text-[#0066b2]"
                            :class="{ 'bg-blue-50/70 text-[#0066b2] font-semibold': selected === '{{ $dist }}' }"
                        >
                            {{ $dist }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button dengan Heroicon Funnel -->
            <div class="md:col-span-1">
                <button type="submit" class="w-full bg-[#0066b2] hover:bg-[#005596] text-white py-2.5 px-3 rounded-xl text-xs font-semibold transition-all shadow-2xs flex items-center justify-center gap-1.5 active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    <span>Filter</span>
                </button>
            </div>
        </form>

        <!-- Ekraf Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10 pt-2">
            @forelse($ekrafs as $item)
                <article class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="aspect-video w-full overflow-hidden bg-slate-100 relative">
                            <img 
                                src="{{ $item->featured_image ? asset('storage/' . $item->featured_image) : 'https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?auto=format&fit=crop&w=800&q=80' }}" 
                                alt="{{ $item->title }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            @if($item->category)
                                <span class="absolute top-3 left-3 bg-[#0066b2] text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-xs uppercase">
                                    {{ $item->category->name }}
                                </span>
                            @endif
                        </div>
                        <div class="p-5 sm:p-6 space-y-3">
                            <h3 class="font-bold text-slate-900 text-base sm:text-lg uppercase leading-snug line-clamp-2 group-hover:text-[#0066b2] transition-colors">
                                {{ $item->title }}
                            </h3>
                            <p class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#0066b2] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span>{{ $item->location_name ?? '-' }} {{ $item->district ? '('.$item->district.')' : '' }}</span>
                            </p>
                            <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($item->description), 110) }}
                            </p>
                        </div>
                    </div>

                    <div class="px-5 sm:px-6 pb-5 sm:pb-6 pt-3 flex items-center justify-between text-xs sm:text-sm text-slate-400 border-t border-slate-100">
                        <span class="flex items-center gap-1 text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            {{ $item->published_at ? $item->published_at->format('M d, Y') : $item->created_at->format('M d, Y') }}
                        </span>
                        <a 
                            href="{{ url('/publikasi/ragam-ekonomi-kreatif/' . $item->slug) }}" 
                            class="bg-[#0066b2] hover:bg-[#005596] text-white font-semibold px-4 py-2 rounded-lg transition-colors text-xs sm:text-sm shadow-2xs"
                        >
                            Selengkapnya
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-16 bg-white rounded-xl border border-slate-200/80 text-slate-500">
                    <p class="text-base font-semibold">Tidak ada produk / usaha ekonomi kreatif yang ditemukan.</p>
                    <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter kategori kamu.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $ekrafs->links() }}
        </div>

    </div>
</section>
@endsection