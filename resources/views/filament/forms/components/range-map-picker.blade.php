@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <style>
        .leaflet-container {
            background-color: #0f172a !important;
            font-family: inherit;
        }
    </style>
@endonce

<div
    wire:ignore
    x-data="{
        map: null,
        marker: null,
        imageUrl: '{{ asset('images/peta_sebaran_wilayah.webp') }}',
        initMap() {
            if (typeof L === 'undefined') {
                setTimeout(() => this.initMap(), 100);
                return;
            }

            if (this.map) return;

            // Muat rasio aspek asli gambar secara otomatis
            const img = new Image();
            img.src = this.imageUrl;
            img.onload = () => {
                const heightRatio = (img.naturalHeight / img.naturalWidth) * 1000;
                const bounds = [[0, 0], [heightRatio, 1000]];

                this.map = L.map(this.$refs.mapContainer, {
                    crs: L.CRS.Simple,
                    minZoom: -1,
                    maxZoom: 3,
                    zoomSnap: 0.25,
                    attributionControl: false
                });

                L.imageOverlay(this.imageUrl, bounds).addTo(this.map);
                this.map.fitBounds(bounds);
                this.map.setMaxBounds(bounds);

                let rawLat = parseFloat($wire.get('data.latitude'));
                let rawLng = parseFloat($wire.get('data.longitude'));

                let currentY = (!isNaN(rawLat) && rawLat >= 0 && rawLat <= heightRatio) ? rawLat : (heightRatio / 2);
                let currentX = (!isNaN(rawLng) && rawLng >= 0 && rawLng <= 1000) ? rawLng : 500;

                this.marker = L.marker([currentY, currentX], { draggable: true }).addTo(this.map);

                if (rawLat !== currentY || rawLng !== currentX) {
                    this.updateCoordinates(currentY, currentX);
                }

                // Handler Klik Peta
                this.map.on('click', (e) => {
                    let y = e.latlng.lat;
                    let x = e.latlng.lng;
                    if (x >= 0 && x <= 1000 && y >= 0 && y <= heightRatio) {
                        this.marker.setLatLng(e.latlng);
                        this.updateCoordinates(y, x);
                    }
                });

                // Handler Geser Marker
                this.marker.on('dragend', (e) => {
                    let pos = e.target.getLatLng();
                    this.updateCoordinates(pos.lat, pos.lng);
                });

                setTimeout(() => this.map.invalidateSize(), 200);
            };
        },
        updateCoordinates(y, x) {
            $wire.set('data.latitude', y.toFixed(2));
            $wire.set('data.longitude', x.toFixed(2));
        }
    }"
    x-init="initMap()"
    class="w-full my-3"
>
    <div class="mb-2 text-xs text-slate-400">
        Klik langsung pada wilayah gambar peta untuk menancapkan Pin Point.
    </div>
    <div 
        x-ref="mapContainer" 
        class="w-full rounded-xl border border-gray-700 shadow-sm relative overflow-hidden" 
        style="height: 520px; min-height: 450px; width: 100%; z-index: 1;"
    ></div>
</div>