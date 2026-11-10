@extends('layouts.app')

@section('title', 'Beranda - Dinas Pariwisata dan Ekonomi Kreatif Kab. Merauke')

@section('content')

@php
// Ambil data siteSetting jika belum dibagikan oleh ServiceProvider
$siteSetting = $siteSetting ?? \App\Models\SiteSetting::first();
@endphp

{{-- 1. HERO SLIDER SECTION (Dynamic with Alpine.js Autoplay) --}}
<section 
    class="relative overflow-hidden" 
    x-data="{ 
        activeSlide: 0, 
        totalSlides: {{ count($slides) > 0 ? count($slides) : 1 }},
        timer: null,
        startAutoSlide() {
            if (this.totalSlides > 1) {
                this.timer = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            }
        },
        stopAutoSlide() {
            if (this.timer) clearInterval(this.timer);
        },
        nextSlide() {
            this.activeSlide = (this.activeSlide === this.totalSlides - 1) ? 0 : this.activeSlide + 1;
        },
        prevSlide() {
            this.activeSlide = (this.activeSlide === 0) ? this.totalSlides - 1 : this.activeSlide - 1;
        }
    }"
    x-init="startAutoSlide()"
    @mouseenter="stopAutoSlide()"
    @mouseleave="startAutoSlide()"
>
    <div class="relative h-130 sm:h-162.5 lg:h-180 w-full flex items-center justify-center">
        @forelse($slides as $index => $slide)
            <div 
                x-show="activeSlide === {{ $index }}"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 w-full h-full"
            >
                <!-- Gambar Banner -->
                <img 
                    src="{{ asset('storage/' . $slide->image_path) }}" 
                    alt="{{ $slide->title ?? 'Slide Banner' }}" 
                    class="w-full h-full object-cover"
                />
                
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 sm:px-12 lg:px-24 py-12">
                    
                    <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">
                        @if(!empty($slide->title))
                            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight drop-shadow-lg">
                                {{ $slide->title }}
                            </h1>
                        @endif
                        
                        @if(!empty($slide->description))
                            <p class="text-sm sm:text-base lg:text-lg text-slate-200/90 leading-relaxed max-w-2xl mx-auto drop-shadow-md font-medium">
                                {{ $slide->description }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            {{-- Fallback jika belum ada slide di database --}}
            <div class="absolute inset-0 w-full h-full">
                <img 
                    src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80" 
                    alt="Monumen Kapsul Waktu" 
                    class="w-full h-full object-cover"
                />
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 sm:px-12 lg:px-24 py-12">
                    <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">
                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight drop-shadow-lg">
                            Monumen Kapsul Waktu
                        </h1>
                        <p class="text-sm sm:text-base lg:text-lg text-slate-200/90 leading-relaxed max-w-2xl mx-auto drop-shadow-md font-medium">
                            Selamat datang di portal resmi {{ $siteSetting?->nama_instansi ?? 'Dinas Pariwisata dan Ekonomi Kreatif' }} {{ $siteSetting?->nama_daerah ?? 'Kabupaten Merauke' }}.
                        </p>
                    </div>
                </div>
            </div>
        @endforelse

        {{-- Navigation Arrows --}}
        <button 
            @click="prevSlide()" 
            class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 text-white/80 hover:text-white bg-black/30 hover:bg-black/60 backdrop-blur-md w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center text-lg sm:text-xl transition-all z-20 border border-white/10 shadow-lg cursor-pointer" 
            aria-label="Previous"
        >❮</button>
        <button 
            @click="nextSlide()" 
            class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 text-white/80 hover:text-white bg-black/30 hover:bg-black/60 backdrop-blur-md w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center text-lg sm:text-xl transition-all z-20 border border-white/10 shadow-lg cursor-pointer" 
            aria-label="Next"
        >❯</button>

        {{-- Slide Indicators --}}
        @if(count($slides) > 1)
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
                @foreach($slides as $index => $slide)
                    <button 
                        @click="activeSlide = {{ $index }}" 
                        class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                        :class="activeSlide === {{ $index }} ? 'w-8 bg-[#0066b2]' : 'w-2 bg-white/50 hover:bg-white/80'"
                        aria-label="Go to slide {{ $index + 1 }}"
                    ></button>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- 2. LOGO PARIWISATA SECTION (DYNAMIC SITE SETTINGS) --}}
<section class="w-full py-16 sm:py-20 bg-white border-b border-slate-200">
    <div class="w-full px-6 sm:px-12 lg:px-16">
        
        <!-- Asterisk Separator & Title Dinamis -->
        <div class="text-center mb-12 sm:mb-14">
            <span class="text-slate-400 text-2xl block">✻</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-800 uppercase tracking-wide mt-2">
                {{ $siteSetting?->logo_title ?? 'LOGO PARIWISATA MERAUKE' }}
            </h2>
            <p class="text-sm sm:text-base text-slate-500 mt-3 max-w-4xl mx-auto leading-relaxed">
                {{ $siteSetting?->logo_subtitle ?? 'Tiga ikon di Kabupaten Merauke yaitu Musamus (rumah semut), Saham (kanguru), dan matahari, memiliki makna khusus sebagai identitas pariwisata' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-16 items-center">
            <!-- Explanations Left (Rich Text with Custom List Styling) -->
            <div class="md:col-span-7 text-sm sm:text-base text-slate-700 leading-relaxed [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-4 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-4 [&_p]:mb-4">
                @if(!empty($siteSetting?->logo_description))
                    {!! $siteSetting->logo_description !!}
                @else
                    <ol>
                        <li>
                            <strong class="font-bold text-slate-900 text-base sm:text-lg">Musamus (Rumah Semut):</strong> Hanya ada di Merauke, melambangkan kebersamaan masyarakat yang bekerja sama tanpa merusak lingkungan. Inspirasi untuk membangun Merauke menjadi kota pariwisata tanpa merusak alam.
                        </li>
                        <li>
                            <strong class="font-bold text-slate-900 text-base sm:text-lg">Saham (Kanguru):</strong> Melambangkan kemampuan melompat cepat sambil membawa anaknya. Merauke diidentikkan sebagai induk kanguru yang membawa masyarakatnya menuju kemakmuran melalui pariwisata.
                        </li>
                        <li>
                            <strong class="font-bold text-slate-900 text-base sm:text-lg">Matahari:</strong> Merauke terletak di ujung timur Indonesia, menjadi daerah pertama yang merasakan sinar matahari pagi, simbol daerah yang bangun lebih awal dibandingkan wilayah lain di indonesia tengah dan barat.
                        </li>
                    </ol>
                @endif
            </div>

            <!-- Logo Image Right Dinamis -->
            <div class="md:col-span-5 flex justify-center">
                <div class="p-2 w-full max-w-md lg:max-w-lg">
                    <img 
                        src="{{ $siteSetting?->logo_pariwisata ? asset('storage/' . $siteSetting->logo_pariwisata) : asset('images/logodispar.webp') }}" 
                        alt="{{ $siteSetting?->logo_title ?? 'Logo Pariwisata' }}" 
                        class="w-full h-auto object-contain drop-shadow-md hover:scale-105 transition-transform duration-300"
                    />
                </div>
            </div>
        </div>

    </div>
</section>

{{-- 3. BERITA & AGENDA GRID SECTION --}}
<section class="w-full py-16 sm:py-20 bg-slate-50">
    <div class="w-full px-6 sm:px-12 lg:px-16">
        
        <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-8 sm:mb-10">Berita</h2>

        <!-- Grid 3 Kolom Full Width -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10">
            @forelse($items as $item)
                <article class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col justify-between">
                    <div>
                        <div class="aspect-video w-full overflow-hidden bg-slate-100">
                            <img 
                                src="{{ $item->featured_image ? asset('storage/' . $item->featured_image) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80' }}" 
                                alt="{{ $item->title }}" 
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            />
                        </div>
                        <div class="p-5 sm:p-6 space-y-3">
                            <h3 class="font-bold text-slate-900 text-base sm:text-lg uppercase leading-snug line-clamp-2">
                                {{ $item->title }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($item->content), 120) }}
                            </p>
                        </div>
                    </div>

                    <div class="px-5 sm:px-6 pb-5 sm:pb-6 pt-3 flex items-center justify-between text-xs sm:text-sm text-slate-400 border-t border-slate-100">
                        <span>{{ $item->event_date ? $item->event_date->format('M d, Y') : $item->created_at->format('M d, Y') }}</span>
                        <a 
                            href="{{ url('/agenda/' . $item->slug) }}" 
                            class="bg-[#0066b2] hover:bg-[#005596] text-white font-semibold px-4 py-2 rounded-md transition-colors text-xs sm:text-sm"
                        >
                            Selengkapnya
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-xl border border-slate-200 text-slate-500">
                    Belum ada berita atau agenda terbaru.
                </div>
            @endforelse
        </div>

    </div>
</section>

@endsection