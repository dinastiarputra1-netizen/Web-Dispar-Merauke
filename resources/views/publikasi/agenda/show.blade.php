@extends('layouts.app')

@section('title', $agenda->title . ' | Dinas Pariwisata Kab. Merauke')

@section('content')
<section class="w-full bg-[#f8fafc] py-10 sm:py-14 min-h-screen">
    <div class="w-full px-6 sm:px-12 lg:px-16">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Konten Utama (Left) -->
            <div class="lg:col-span-8 space-y-6">
                
                <div class="space-y-6">
                    
                    <!-- Title & Meta -->
                    <div class="space-y-3 border-b border-slate-100 pb-5">
                        @if($agenda->category)
                            <span class="bg-[#0066b2] text-white text-[10px] font-bold px-3 py-1 rounded-md tracking-wider uppercase">
                                {{ $agenda->category->name }}
                            </span>
                        @endif

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
                            {{ $agenda->title }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 font-medium pt-1">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                {{ $agenda->published_at ? $agenda->published_at->format('d F Y') : $agenda->created_at->format('d F Y') }}
                            </span>

                            @if($agenda->event_date)
                                <span class="bg-blue-50 text-[#0066b2] border border-blue-100 px-2.5 py-1 rounded-md font-semibold">
                                    Pelaksanaan Event: {{ $agenda->event_date->format('d F Y') }}
                                </span>
                            @endif

                            <span>Oleh: <strong class="text-slate-700">{{ $agenda->author->name ?? 'Admin' }}</strong></span>
                        </div>
                    </div>

                    @php
                        $galleryImages = array_values(array_filter($agenda->gallery_images ?? []));

                        if ($galleryImages === [] && $agenda->youtube_embed_url && $agenda->featured_image) {
                            $galleryImages = [$agenda->featured_image];
                        }
                    @endphp

                    @if($agenda->youtube_embed_url)
                        <div class="aspect-video w-full overflow-hidden rounded-xl border border-slate-200 bg-black shadow-sm">
                            <iframe
                                src="{{ $agenda->youtube_embed_url }}"
                                title="Video {{ $agenda->title }}"
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
                                src="{{ $agenda->featured_image ? asset('storage/' . $agenda->featured_image) : 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80' }}"
                                alt="{{ $agenda->title }}"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    @endif

                    @if($galleryImages !== [])
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3" aria-label="Galeri foto {{ $agenda->title }}">
                            @foreach(array_slice($galleryImages, 0, 3) as $index => $imagePath)
                                <div class="aspect-[4/3] overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                    <img
                                        src="{{ asset('storage/' . $imagePath) }}"
                                        alt="{{ $agenda->title }} - Foto {{ $index + 1 }}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                    />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Narasi Rich Text -->
                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-base 
                        [&_p]:mb-4 [&_p]:leading-relaxed 
                        [&_h1]:text-2xl [&_h1]:font-bold [&_h1]:text-slate-900 [&_h1]:mt-6 [&_h1]:mb-3 
                        [&_h2]:text-xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:mt-6 [&_h2]:mb-3 
                        [&_h3]:text-lg [&_h3]:font-bold [&_h3]:text-slate-900 [&_h3]:mt-5 [&_h3]:mb-2 
                        [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:mb-4 [&_ul]:space-y-1.5 
                        [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:mb-4 [&_ol]:space-y-1.5 
                        [&_strong]:font-bold [&_strong]:text-slate-900 
                        [&_a]:text-[#0066b2] [&_a]:underline">
                        
                        {!! \Illuminate\Support\Str::contains($agenda->content, ['<p>', '<ul>', '<ol>', '<div>', '<br>']) 
                            ? $agenda->content 
                            : nl2br(e($agenda->content)) 
                        !!}
                    </div>

                </div>

            </div>

            <!-- Sidebar (Right) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Back Button -->
                <a 
                    href="{{ route('agenda.index') }}" 
                    class="inline-flex items-center gap-2 bg-white border border-slate-200/80 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm w-full justify-center"
                >
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>Kembali ke Agenda & Berita</span>
                </a>

                <!-- Agenda Terbaru Lainnya -->
                <div class="bg-white rounded-xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">
                        Agenda & Berita Terkait
                    </h3>

                    <div class="space-y-4">
                        @foreach($relatedAgendas as $rel)
                            <a href="{{ route('agenda.show', $rel->slug) }}" class="flex gap-3 group">
                                <div class="w-20 h-16 rounded-lg overflow-hidden bg-slate-100 shrink-0">
                                    <img 
                                        src="{{ $rel->featured_image ? asset('storage/' . $rel->featured_image) : 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=300&q=80' }}" 
                                        alt="{{ $rel->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    />
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-xs text-slate-800 group-hover:text-[#0066b2] line-clamp-2 transition-colors">
                                        {{ $rel->title }}
                                    </h4>
                                    <p class="text-[11px] text-slate-400">
                                        {{ $rel->published_at ? $rel->published_at->format('d M Y') : $rel->created_at->format('d M Y') }}
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
