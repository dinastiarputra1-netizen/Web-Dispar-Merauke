@extends('layouts.app')

@section('title', ($page->title ?? 'Struktur Organisasi') . ' | Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke')

@section('content')
<!-- Header Banner Full-Width dengan Warna Background #f1f5f9 -->
<section class="w-full bg-[#f1f5f9] border-b border-slate-200/70 py-8 sm:py-10">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-2">
        <nav class="flex text-xs text-slate-500 font-medium space-x-2">
            <a href="{{ route('home') }}" class="hover:text-[#0066b2] transition-colors">Beranda</a>
            <span>/</span>
            <span>Profil</span>
            <span>/</span>
            <span class="text-[#0066b2] font-semibold">{{ $page->title ?? 'Struktur Organisasi' }}</span>
        </nav>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            {{ $page->title ?? 'Struktur Organisasi' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">
            Bagan tata kerja dan pembagian struktur jajaran Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke.
        </p>
    </div>
</section>

<!-- Main Content Area -->
<section class="w-full bg-[#f8fafc] py-8 sm:py-12 min-h-screen">
  <div class="w-full px-6 sm:px-12 lg:px-16">

    <!-- Grid 2 Kolom Layout -->
    <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">
      
      <!-- KOLOM KIRI: Konten Utama Struktur Organisasi (70% Lebar) -->
      <main class="w-full lg:w-[70%] bg-white rounded-xl p-6 sm:p-10 border border-slate-200/80 shadow-sm space-y-8">

        <!-- Tampilan Gambar Bagan Diagram Utama -->
        <div class="space-y-4">
          <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 01-2-2h-2a2 2 0 01-2 2"/>
            </svg>
            <span>Bagan Diagram Organisasi</span>
          </h3>
          
          <div class="w-full p-4 bg-slate-50 border border-slate-200/80 rounded-xl overflow-x-auto shadow-inner">
            <img 
              src="{{ !empty($page->featured_image) ? asset('storage/' . $page->featured_image) : asset('images/Struktur_Organisasi.svg') }}" 
              alt="Diagram Struktur Organisasi Dinas Pariwisata Merauke" 
              class="w-full min-w-162.5 h-auto object-contain mx-auto rounded-lg"
            />
          </div>
        </div>

        <!-- Narasi Konten / Penjelasan Tambahan dari Admin Dashboard -->
        @if(!empty($page->content))
          <div class="pt-4 border-t border-slate-100 prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-base 
            prose-p:mb-4 prose-p:leading-relaxed 
            prose-headings:font-bold prose-headings:text-slate-900 prose-headings:mt-6 prose-headings:mb-3 
            prose-ul:list-disc prose-ul:pl-5 prose-ul:mb-4 prose-ul:space-y-2 
            prose-ol:list-decimal prose-ol:pl-5 prose-ol:mb-4 prose-ol:space-y-2 
            prose-strong:font-bold prose-strong:text-slate-900 
            prose-a:text-[#0066b2] prose-a:no-underline hover:prose-a:underline">
            {!! \Illuminate\Support\Str::contains($page->content, ['<p>', '<ul>', '<ol>', '<div>', '<br>']) 
                ? $page->content 
                : nl2br(e($page->content)) 
            !!}
          </div>
        @endif

      </main>

      <!-- KOLOM KANAN: Navigasi Menu Profil (30% Lebar) -->
      <aside class="w-full lg:w-[30%] space-y-6">
        
        <div class="bg-white rounded-xl p-6 border border-slate-200/80 shadow-sm space-y-4">
          <h3 class="font-bold text-slate-900 text-base sm:text-lg border-b border-slate-100 pb-3">
            Navigasi Profil
          </h3>

          @php
            $profileNav = [
              ['name' => 'Profil Lembaga', 'url' => url('/profil-lembaga'), 'active' => request()->is('profil-lembaga*')],
              ['name' => 'Struktur Organisasi', 'url' => url('/struktur-organisasi'), 'active' => request()->is('struktur-organisasi*')],
            ];
          @endphp

          <nav class="flex flex-col space-y-1">
            @foreach($profileNav as $item)
              <a
                href="{{ $item['url'] }}"
                class="px-4 py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-colors flex items-center justify-between {{ $item['active'] ? 'bg-[#0066b2] text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-[#0066b2]' }}"
              >
                <span>{{ $item['name'] }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </a>
            @endforeach
          </nav>
        </div>

        <!-- Quick Contacts Widget -->
        <div class="bg-blue-50/60 rounded-xl p-6 border border-blue-100 space-y-3">
          <h4 class="font-bold text-slate-900 text-sm sm:text-base">Layanan Informasi</h4>
          <p class="text-xs text-slate-600 leading-relaxed">
            Memiliki pertanyaan seputar kemitraan atau informasi pariwisata Merauke?
          </p>
          <a 
            href="https://wa.me/6282199090097" 
            target="_blank"
            rel="noopener noreferrer"
            class="inline-block bg-[#0066b2] hover:bg-[#005596] text-white font-semibold text-xs px-4 py-2 rounded-lg transition-colors"
          >
            Hubungi Kontak Resmi
          </a>
        </div>

      </aside>

    </div>

  </div>
</section>
@endsection