@extends('layouts.app')

@section('title', $ekraf->title . ' | Dinas Pariwisata Kab. Merauke')

@section('content')
<section class="w-full bg-[#f8fafc] py-10 sm:py-14 min-h-screen">
    <div class="w-full px-6 sm:px-12 lg:px-16">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Konten Utama (Left) - Tanpa Box Pembungkus -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Title & Meta -->
                <div class="space-y-3 border-b border-slate-200/80 pb-5">
                    @if($ekraf->category)
                        <span class="bg-[#0066b2] text-white text-[10px] font-bold px-3 py-1 rounded-md tracking-wider uppercase">
                            {{ $ekraf->category->name }}
                        </span>
                    @endif

                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-snug">
                        {{ $ekraf->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-5 text-xs sm:text-sm text-slate-500 font-medium pt-1">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            {{ $ekraf->published_at ? $ekraf->published_at->format('d F Y') : $ekraf->created_at->format('d F Y') }}
                        </span>
                        @if($ekraf->location_name || $ekraf->district)
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                {{ $ekraf->location_name ?? '' }} {{ $ekraf->district ? '('.$ekraf->district.')' : '' }}
                            </span>
                        @endif
                    </div>
                </div>

                @php
                    $galleryImages = array_values(array_filter($ekraf->gallery_images ?? []));

                    if ($galleryImages === [] && $ekraf->youtube_embed_url && $ekraf->featured_image) {
                        $galleryImages = [$ekraf->featured_image];
                    }
                @endphp

                @if($ekraf->youtube_embed_url)
                    <div class="aspect-video w-full overflow-hidden rounded-xl border border-slate-200 bg-black shadow-sm">
                        <iframe
                            src="{{ $ekraf->youtube_embed_url }}"
                            title="Video {{ $ekraf->title }}"
                            class="h-full w-full border-0"
                            loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>
                @else
                    <div class="aspect-video w-full overflow-hidden rounded-xl border border-slate-200 bg-slate-100 shadow-sm">
                        <img
                            src="{{ $ekraf->featured_image ? asset('storage/' . $ekraf->featured_image) : 'https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?auto=format&fit=crop&w=1200&q=80' }}"
                            alt="{{ $ekraf->title }}"
                            class="h-full w-full object-cover"
                        />
                    </div>
                @endif

                @if($galleryImages !== [])
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3" aria-label="Galeri foto {{ $ekraf->title }}">
                        @foreach(array_slice($galleryImages, 0, 3) as $index => $imagePath)
                            <div class="aspect-[4/3] overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                <img
                                    src="{{ asset('storage/' . $imagePath) }}"
                                    alt="{{ $ekraf->title }} - Foto {{ $index + 1 }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                />
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Narasi Deskripsi Rich Text -->
                <div class="max-w-none text-slate-700 leading-relaxed text-sm sm:text-base
                    [&_p]:mb-4 [&_p]:leading-relaxed
                    [&_h1]:text-2xl [&_h1]:font-bold [&_h1]:text-slate-900 [&_h1]:mt-6 [&_h1]:mb-3
                    [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:mt-6 [&_h2]:mb-3
                    [&_h3]:text-lg [&_h3]:font-bold [&_h3]:text-slate-900 [&_h3]:mt-5 [&_h3]:mb-2
                    [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:mb-4 [&_ul]:space-y-1.5
                    [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:mb-4 [&_ol]:space-y-1.5
                    [&_li]:pl-1 [&_strong]:font-bold [&_strong]:text-slate-900
                    [&_a]:text-[#0066b2] [&_a]:underline [&_img]:rounded-xl">
                    {!! \Illuminate\Support\Str::contains($ekraf->description, ['<p>', '<ul>', '<ol>', '<div>', '<br>']) 
                        ? $ekraf->description 
                        : nl2br(e($ekraf->description)) 
                    !!}
                </div>

                <!-- Location & Google Maps Section -->
                @if($ekraf->latitude && $ekraf->longitude)
                    <div class="pt-6 border-t border-slate-200/80 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-base sm:text-xl flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.813c-.317-.159-.69-.159-1.006 0L3.628 5.747C3.247 5.937 3 6.327 3 6.753v11.427c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                                </svg>
                                <span>Lokasi Galeri / Sentra Industri</span>
                            </h3>
                        </div>

                        <div class="w-full aspect-video sm:aspect-21/9 rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs relative bg-slate-100">
                            <iframe 
                                class="w-full h-full border-0" 
                                loading="lazy" 
                                allowfullscreen 
                                referrerpolicy="no-referrer-when-downgrade" 
                                src="https://maps.google.com/maps?q={{ $ekraf->latitude }},{{ $ekraf->longitude }}&hl=id&z=14&output=embed">
                            </iframe>
                        </div>

                        <div class="bg-white border border-slate-200/80 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
                            <div class="space-y-1">
                                <p class="text-xs sm:text-sm font-bold text-slate-800">
                                    {{ $ekraf->location_name ?? 'Lokasi Usaha' }} {{ $ekraf->district ? '— Distrik ' . $ekraf->district : '' }}
                                </p>
                                <p class="text-[11px] sm:text-xs text-slate-500 font-mono">
                                    Koordinat: {{ $ekraf->latitude }}, {{ $ekraf->longitude }}
                                </p>
                            </div>
                            <a 
                                href="https://maps.google.com/?q={{ $ekraf->latitude }},{{ $ekraf->longitude }}" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 bg-[#0066b2] hover:bg-[#005596] text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-colors shadow-sm shrink-0"
                            >
                                <span>Buka Google Maps</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Sidebar (Right) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Back Button -->
                <a 
                    href="{{ url('/publikasi/ragam-ekonomi-kreatif') }}" 
                    class="inline-flex items-center gap-2 bg-white border border-slate-200/80 px-4 py-3 rounded-xl text-xs sm:text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-[#0066b2] transition-colors shadow-sm w-full justify-center"
                >
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>Kembali ke Ragam Ekonomi</span>
                </a>

                <!-- Ekraf Lainnya (Ukuran Card Diperbesar) -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-5">
                    <h3 class="font-bold text-slate-900 text-base sm:text-lg border-b border-slate-100 pb-3">
                        Produk / Usaha Kreatif Lainnya
                    </h3>

                    <div class="space-y-4">
                        @foreach($relatedEkrafs as $rel)
                            <a href="{{ url('/publikasi/ragam-ekonomi-kreatif/' . $rel->slug) }}" class="flex items-center gap-4 p-2.5 -mx-2.5 rounded-xl hover:bg-slate-50 group transition-all">
                                <!-- Thumbnail Diperbesar (w-28 h-20) -->
                                <div class="w-28 h-20 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200/60 shadow-2xs">
                                    <img 
                                        src="{{ $rel->featured_image ? asset('storage/' . $rel->featured_image) : 'https://images.unsplash.com/photo-1528698827591-e19ccd7bc23d?auto=format&fit=crop&w=400&q=80' }}" 
                                        alt="{{ $rel->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                </div>
                                <div class="space-y-1.5 flex-1 min-w-0">
                                    <h4 class="font-bold text-sm text-slate-800 group-hover:text-[#0066b2] line-clamp-2 transition-colors leading-snug">
                                        {{ $rel->title }}
                                    </h4>
                                    <p class="text-xs text-slate-500 flex items-center gap-1 font-medium">
                                        <svg class="w-3.5 h-3.5 text-[#0066b2] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        <span class="truncate">{{ $rel->district ?? 'Merauke' }}</span>
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>
@endsection