@php
// Ambil data siteSetting jika belum dibagikan oleh ServiceProvider
$siteSetting = $siteSetting ?? \App\Models\SiteSetting::first();
@endphp

<footer class="w-full border-t border-slate-200 bg-slate-100 pt-10 pb-6 text-slate-800 sm:pt-16 sm:pb-8">
    <div class="w-full px-4 sm:px-12 lg:px-16">
        
        <!-- Grid Utama -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-10 lg:gap-16 pb-8 md:pb-10">
            
            <!-- 1. AREA BRAND & KONTAK (Di Atas pada Mobile, di Kiri pada Desktop) -->
            <div class="md:col-span-6 space-y-4">
                <div class="flex min-w-0 items-start gap-3 sm:gap-4">
                    <img 
                        src="{{ $siteSetting?->logo_dinas ? asset('storage/' . $siteSetting->logo_dinas) : asset('images/logodisparmerauke.webp') }}" 
                        alt="Logo Dinas Pariwisata" 
                        class="mt-1 h-14 w-auto shrink-0 object-contain sm:h-24"
                    />
                    <div class="min-w-0 space-y-1">
                        <h3 class="break-words text-sm font-bold leading-snug text-slate-900 sm:text-lg">
                            {{ $siteSetting?->nama_instansi ?? 'Dinas Pariwisata Dan Ekonomi Kreatif Kabupaten Merauke' }}
                        </h3>
                        <p class="break-words pt-1 text-xs text-slate-600 sm:text-sm">
                            {{ $siteSetting?->alamat ?? 'Jln. Trikora nomor 15 Merauke — Provinsi Papua Selatan' }}
                        </p>
                        @if($siteSetting?->email)
                            <p class="text-xs sm:text-sm text-slate-600">Email : {{ $siteSetting->email }}</p>
                        @else
                            <p class="text-xs sm:text-sm text-slate-600">Email : pariwisatamerauke.id@gmail.com</p>
                        @endif

                        @if($siteSetting?->telepon)
                            <p class="text-xs sm:text-sm text-slate-600">Whatsapp / Telepon : {{ $siteSetting->telepon }}</p>
                        @else
                            <p class="text-xs sm:text-sm text-slate-600">Whatsapp / Telepon : +62 821-9909-0097</p>
                        @endif
                    </div>
                </div>

                <!-- Social Media Icons -->
                <div class="flex items-center gap-3 pt-3">
                    @php
                        $waNumber = $siteSetting?->telepon ? preg_replace('/[^0-9]/', '', $siteSetting->telepon) : '6282199090097';
                    @endphp
                    
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#25D366] text-white flex items-center justify-center hover:scale-110 hover:shadow-md transition-all shadow-sm" aria-label="WhatsApp">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </a>

                    {{-- Instagram --}}
                    @if($siteSetting?->instagram)
                        <a href="{{ $siteSetting->instagram }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#E1306C] text-white flex items-center justify-center hover:scale-110 hover:shadow-md transition-all shadow-sm" aria-label="Instagram">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    @endif

                    {{-- Facebook --}}
                    @if($siteSetting?->facebook)
                        <a href="{{ $siteSetting->facebook }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:scale-110 hover:shadow-md transition-all shadow-sm" aria-label="Facebook">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif

                    {{-- YouTube --}}
                    @if($siteSetting?->youtube)
                        <a href="{{ $siteSetting->youtube }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#FF0000] text-white flex items-center justify-center hover:scale-110 hover:shadow-md transition-all shadow-sm" aria-label="YouTube">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif

                    {{-- TikTok --}}
                    @if($siteSetting?->tiktok)
                        <a href="{{ $siteSetting->tiktok }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#111111] text-white flex items-center justify-center hover:scale-110 hover:shadow-md transition-all shadow-sm" aria-label="TikTok">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.1 2.68 1.61 4.19 1.78v4.03c-1.41-.05-2.82-.34-4.1-.95-.55-.25-1.06-.57-1.56-.9-.01 2.77.01 5.54-.02 8.31-.07 1.33-.5 2.64-1.24 3.75-1.2 1.82-3.28 3.01-5.46 3.05-1.34.08-2.68-.28-3.82-.98-1.89-1.16-3.22-3.29-3.24-5.53-.02-.28-.01-.56.01-.83.2-2.13 1.25-4.13 3.08-5.25 1.54-.99 3.47-1.31 5.26-.92.02 1.49 0 2.98.01 4.47-1.02-.33-2.17-.24-3.09.34-.67.42-1.15 1.12-1.26 1.91-.15 1 .13 2.07.88 2.74.55.51 1.31.78 2.05.74.76-.04 1.5-.39 1.98-.99.37-.46.55-1.04.55-1.63.02-3.52.01-7.03.02-10.55z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- 2. AREA MENU LINK (Grid Paksa 2 Kolom untuk Mobile) -->
            <div class="md:col-span-6 grid grid-cols-2 gap-4 sm:gap-8 pt-6 border-t border-slate-200 md:pt-0 md:border-t-0">
                
                <!-- Kiri: Tentang Kami -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base mb-3">Tentang Kami</h4>
                    <ul class="space-y-2 text-[11px] sm:text-sm text-slate-600">
                        <li><a href="{{ url('/profil-lembaga') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Profil Lembaga</a></li>
                        <li><a href="{{ url('/struktur-organisasi') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Struktur Organisasi</a></li>
                        <li><a href="{{ url('/publikasi/ragam-wisata') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Ragam Wisata</a></li>
                        <li><a href="{{ url('/publikasi/ragam-ekonomi-kreatif') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Ragam Ekonomi Kreatif</a></li>
                        <li><a href="{{ url('/publikasi/peta-sebaran-wilayah') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Peta Sebaran Wilayah</a></li>
                        <li><a href="{{ url('/statistik/akomodasi/hotel-dan-penginapan') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Hotel & Penginapan</a></li>
                        <li><a href="{{ url('/statistik/akomodasi/restoran') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Restoran</a></li>
                        <li><a href="{{ url('/agenda') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Informasi Agenda</a></li>
                    </ul>
                </div>

                <!-- Kanan: Kebijakan Privasi -->
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-900 text-sm sm:text-base mb-3">Kebijakan Privasi</h4>
                    <ul class="space-y-2 text-[11px] sm:text-sm text-slate-600">
                        <li><a href="{{ url('/kebijakan-privasi') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="{{ url('/disclaimer') }}" class="hover:underline hover:text-[#0066b2] transition-colors">Disclaimer</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- 3. AREA COPYRIGHT (Paling Bawah) -->
        <div class="w-full border-t border-slate-200 pt-6 mt-2 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <p class="text-[11px] sm:text-xs text-slate-500 font-medium">
                &copy; {{ date('Y') }} {{ $siteSetting?->nama_instansi ?? 'Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke' }}.
            </p>
            <p class="text-[10px] sm:text-xs text-slate-400 font-medium">
                Sistem Informasi v1.0
            </p>
        </div>

    </div>
</footer>