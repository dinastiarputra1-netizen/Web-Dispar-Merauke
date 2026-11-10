@extends('layouts.app')

@section('title', 'Agenda & Berita | Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke')

@section('content')
<!-- Header Banner Full-Width dengan Warna Background #f1f5f9 -->
<section class="w-full bg-[#f1f5f9] border-b border-slate-200/70 py-8 sm:py-10">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-2">
        <nav class="flex text-xs text-slate-500 font-medium space-x-2">
            <a href="{{ route('home') }}" class="hover:text-[#0066b2] transition-colors">Beranda</a>
            <span>/</span>
            <span>Publikasi</span>
            <span>/</span>
            <span class="text-[#0066b2] font-semibold">Agenda & Berita</span>
        </nav>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Agenda & Berita Kegiatan
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">
            Informasi terbaru seputar agenda kerja, berita kepariwisataan, festival kebudayaan, serta pengumuman resmi Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke.
        </p>
    </div>
</section>

<!-- Main Content Area -->
<section class="w-full bg-[#f8fafc] py-8 sm:py-12 min-h-screen">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-8">

        <!-- Filter & Search Bar Custom UI (Tanpa Box Pembungkus) -->
        <form action="{{ route('agenda.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-center">
            
            <!-- Search Input dengan Heroicon Magnifying Glass -->
            <div class="md:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari agenda atau berita..." 
                    class="w-full pl-10 pr-4 py-2.5 text-xs bg-white border border-slate-200/90 rounded-xl focus:outline-none focus:border-[#0066b2] focus:ring-2 focus:ring-[#0066b2]/10 transition-all text-slate-800 placeholder-slate-400 font-medium shadow-2xs hover:border-slate-300"
                />
            </div>

            <!-- Custom Category Dropdown (Alpine.js) -->
            <div 
                x-data="{ 
                    open: false, 
                    selected: '{{ request('kategori') }}', 
                    label: '{{ request('kategori') && isset($categories) ? ($categories->firstWhere('slug', request('kategori'))->name ?? 'Semua Kategori') : 'Semua Kategori' }}' 
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
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <div 
                                @click="selected = '{{ $cat->slug }}'; label = '{{ $cat->name }}'; open = false; $nextTick(() => $el.closest('form').submit())" 
                                class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors text-slate-600 hover:text-[#0066b2]"
                                :class="{ 'bg-blue-50/70 text-[#0066b2] font-semibold': selected === '{{ $cat->slug }}' }"
                            >
                                {{ $cat->name }}
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Custom Year Dropdown (Filter Tahun - Alpine.js) -->
            <div 
                x-data="{ 
                    open: false, 
                    selected: '{{ request('tahun') }}', 
                    label: '{{ request('tahun') ? 'Tahun ' . request('tahun') : 'Semua Tahun' }}' 
                }" 
                class="relative md:col-span-2"
            >
                <input type="hidden" name="tahun" :value="selected">
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
                        @click="selected = ''; label = 'Semua Tahun'; open = false; $nextTick(() => $el.closest('form').submit())" 
                        class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors text-slate-600 hover:text-[#0066b2]"
                        :class="{ 'bg-blue-50/70 text-[#0066b2] font-semibold': selected === '' }"
                    >
                        Semua Tahun
                    </div>
                    @php
                        $currentYear = (int) date('Y');
                    @endphp
                    @for($y = $currentYear; $y >= 2020; $y--)
                        <div 
                            @click="selected = '{{ $y }}'; label = 'Tahun {{ $y }}'; open = false; $nextTick(() => $el.closest('form').submit())" 
                            class="px-4 py-2.5 hover:bg-slate-50 cursor-pointer transition-colors text-slate-600 hover:text-[#0066b2]"
                            :class="{ 'bg-blue-50/70 text-[#0066b2] font-semibold': selected == '{{ $y }}' }"
                        >
                            Tahun {{ $y }}
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Action Buttons: Filter & Reset -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full bg-[#0066b2] hover:bg-[#005596] text-white py-2.5 px-3 rounded-xl text-xs font-semibold transition-all shadow-2xs flex items-center justify-center gap-1.5 active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    <span>Filter</span>
                </button>

                @if(request('search') || request('kategori') || request('tahun'))
                    <a href="{{ route('agenda.index') }}" title="Reset Filter" class="p-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-semibold border border-rose-100 transition-colors shrink-0 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>

        </form>

        <!-- Agenda Grid List -->
        @if($agendas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-2">
                @foreach($agendas as $item)
                    <article class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="space-y-4">
                            <!-- Featured Image -->
                            <div class="aspect-video w-full overflow-hidden bg-slate-100 relative">
                                <img 
                                    src="{{ $item->featured_image ? asset('storage/' . $item->featured_image) : 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80' }}" 
                                    alt="{{ $item->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                                @if($item->category)
                                    <span class="absolute top-3 left-3 bg-[#0066b2] text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-xs">
                                        {{ $item->category->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="px-5 space-y-2">
                                <div class="flex items-center gap-3 text-[11px] text-slate-400 font-medium">
                                    <span>{{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}</span>
                                    @if($item->event_date)
                                        <span>•</span>
                                        <span class="text-[#0066b2] font-semibold">
                                            Event: {{ $item->event_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-[#0066b2] transition-colors line-clamp-2">
                                    <a href="{{ route('agenda.show', $item->slug) }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                    {{ Str::limit(strip_tags($item->content), 120) }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="p-5 pt-3 border-t border-slate-100 mt-4 flex items-center justify-between text-xs">
                            <span class="text-slate-400">
                                Oleh: <strong class="text-slate-600">{{ $item->author->name ?? 'Admin' }}</strong>
                            </span>
                            <a href="{{ route('agenda.show', $item->slug) }}" class="font-bold text-[#0066b2] hover:underline inline-flex items-center gap-1">
                                <span>Baca Detail</span>
                                <span>→</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $agendas->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl p-12 text-center border border-slate-200/80 shadow-sm space-y-3">
                <p class="text-slate-500 text-sm font-medium">Belum ada agenda atau berita publikasi yang ditemukan.</p>
            </div>
        @endif

    </div>
</section>
@endsection