@extends('layouts.app')

@section('title', 'Peta Sebaran Destinasi Wisata | Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke')

@section('content')
<!-- CDN Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .leaflet-container {
        background-color: #f8fafc !important;
        font-family: inherit;
    }
    /* Memaksa warna teks link tombol popup agar selalu terlihat putih */
    .leaflet-popup-content a.popup-btn-link {
        color: #ffffff !important;
        text-decoration: none !important;
    }
</style>

<!-- Header Banner Full-Width dengan Warna Background #f1f5f9 -->
<section class="w-full bg-[#f1f5f9] border-b border-slate-200/70 py-8 sm:py-10">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-2">
        <nav class="flex text-xs text-slate-500 font-medium space-x-2">
            <a href="{{ route('home') }}" class="hover:text-[#0066b2] transition-colors">Beranda</a>
            <span>/</span>
            <span>Publikasi</span>
            <span>/</span>
            <span class="text-[#0066b2] font-semibold">Peta Sebaran Wisata</span>
        </nav>
        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Peta Sebaran Destinasi Wisata
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">
            Peta administratif dan geografis yang memuat titik-titik persebaran objek wisata alam, cagar budaya, sejarah, dan potensi ekonomi kreatif di 20 distrik Kabupaten Merauke.
        </p>
    </div>
</section>

<!-- Main Content Area -->
<section class="w-full bg-[#f8fafc] py-8 sm:py-12 min-h-screen">
    <div class="w-full px-6 sm:px-12 lg:px-16 space-y-8">

        <!-- Interactive Map Viewer Container -->
        <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
        
            <!-- Viewer Action Bar -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2 text-slate-800 font-bold text-base sm:text-lg">
                    <svg class="w-5 h-5 text-[#0066b2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    <span>Peta Tematik Kepariwisataan Merauke</span>
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <a 
                        href="{{ asset('images/peta_sebaran_wilayah.webp') }}" 
                        target="_blank"
                        class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold px-4 py-2 rounded-lg text-xs transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                        <span>Lihat Ukuran Penuh</span>
                    </a>

                    <a 
                        href="{{ asset('images/peta_sebaran_wilayah.webp') }}" 
                        download="Peta_Sebaran_Wisata_Merauke.webp"
                        class="inline-flex items-center gap-2 bg-[#0066b2] hover:bg-[#005596] text-white font-semibold px-4 py-2 rounded-lg text-xs transition-colors shadow-xs"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh Peta (HD)</span>
                    </a>
                </div>
            </div>

            <!-- Map Container -->
            <div class="w-full bg-slate-50 border border-slate-200 rounded-xl overflow-hidden shadow-sm relative z-0">
                <div id="public-image-map" class="w-full" style="height: 650px; min-height: 480px;"></div>
            </div>

        </div>

        <!-- Distrik Breakdown Section -->
        <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            <h3 class="text-base sm:text-lg font-bold text-slate-900 border-b border-slate-100 pb-3">
                Ringkasan Sebaran Wisata per Distrik
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($distrikList ?? [] as $item)
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-5 space-y-2 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-sm sm:text-base">{{ $item['name'] }}</h4>
                            <span class="text-[10px] font-bold text-white px-2.5 py-1 rounded-full {{ $item['color'] }}">
                                {{ $item['count'] }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed pt-1">
                            {{ $item['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

<!-- Leaflet Interactive Map Script -->
<!-- Leaflet Interactive Map Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mapImageUrl = "{{ asset('images/peta_sebaran_wilayah.webp') }}";

        // Fix Bug Icon Marker Leaflet CDN di Laravel
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        });

        const img = new Image();
        img.src = mapImageUrl;
        img.onload = function () {
            // Menghitung rasio aspek asli gambar secara otomatis
            const heightRatio = (this.naturalHeight / this.naturalWidth) * 1000;
            const bounds = [[0, 0], [heightRatio, 1000]];

            const map = L.map('public-image-map', {
                crs: L.CRS.Simple,
                minZoom: -1,
                maxZoom: 4,
                zoomSnap: 0.25,
                attributionControl: false
            });

            L.imageOverlay(mapImageUrl, bounds).addTo(map);
            map.fitBounds(bounds);
            map.setMaxBounds(bounds);

            // Ambil data (Mendukung $mapPoints atau $pinPoints dari controller)
            const mapPoints = @json($mapPoints ?? $pinPoints ?? []);

            console.log("Data Marker Terdeteksi:", mapPoints); // Cek data di F12 Console

            mapPoints.forEach(point => {
                // Konversi koordinat ke float
                const lat = parseFloat(point.latitude);
                const lng = parseFloat(point.longitude);

                // Pengecekan koordinat valid (tanpa membatasi kategori agar semua pin muncul)
                if (!isNaN(lat) && !isNaN(lng)) {
                    
                    const marker = L.marker([lat, lng]).addTo(map);

                    const popupContent = `
                        <div class="p-2 text-center min-w-[180px]">
                            ${point.distrik ? `
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#0066b2] bg-blue-50 px-2 py-0.5 rounded-full mb-1.5 inline-block border border-blue-100">
                                    Distrik ${point.distrik}
                                </span>
                            ` : ''}
                            <h4 class="font-bold text-sm text-slate-900 mb-2">${point.title || point.nama || 'Destinasi'}</h4>
                            ${point.description ? `<p class="text-xs text-slate-500 mb-3 leading-relaxed">${point.description}</p>` : ''}
                            ${(point.link_postingan || point.url) ? `
                                <a href="${point.link_postingan || point.url}" class="popup-btn-link inline-block bg-[#0066b2] hover:bg-[#005596] !text-white text-xs px-3.5 py-2 rounded-lg font-semibold transition-colors shadow-sm" style="color: #ffffff !important;">
                                    Lihat Postingan →
                                </a>
                            ` : ''}
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                }
            });

            setTimeout(() => {
                map.invalidateSize();
                map.fitBounds(bounds);
            }, 150);
        };
    });
</script>
@endsection