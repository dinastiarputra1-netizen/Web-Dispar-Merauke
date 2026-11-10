@php
// Ambil data siteSetting jika belum dibagikan oleh ServiceProvider
$siteSetting = $siteSetting ?? \App\Models\SiteSetting::first();

$navItems = [
    [
        'name' => 'Beranda', 
        'href' => url('/')
    ],
    [ 
        'name' => 'Profil', 
        'href' => '#',
        'children' => [
            ['name' => 'Profil Lembaga', 'href' => url('/profil-lembaga')],
            ['name' => 'Struktur Organisasi', 'href' => url('/struktur-organisasi')],
        ]
    ],
    [ 
        'name' => 'Publikasi', 
        'href' => '#',
        'children' => [
            ['name' => 'Ragam Wisata', 'href' => url('/publikasi/ragam-wisata')],
            ['name' => 'Ragam Ekonomi', 'href' => url('/publikasi/ragam-ekonomi-kreatif')],
            ['name' => 'Peta Sebaran Wilayah', 'href' => url('/publikasi/peta-sebaran-wilayah')],
        ]
    ],
    [ 
        'name' => 'Statistik', 
        'href' => '#',
        'children' => [
            [ 
                'name' => 'Statistik Pariwisata', 
                'href' => '#',
                'children' => [
                    ['name' => 'Statistik Wisatawan Nusantara', 'href' => url('/statistik/wisatawan-nusantara')],
                    ['name' => 'Statistik Wisatawan Mancanegara', 'href' => url('/statistik/wisatawan-mancanegara')],
                    ['name' => 'Statistik Wisatawan Nusantara dan Mancanegara', 'href' => url('/statistik/wisatawan-nasional')],
                    [ 
                        'name' => 'Statistik Akomodasi', 
                        'href' => '#',
                        'children' => [
                            ['name' => 'Hotel dan Penginapan', 'href' => url('/statistik/akomodasi/hotel-dan-penginapan')],
                            ['name' => 'Restoran', 'href' => url('/statistik/akomodasi/restoran')],
                        ]
                    ],
                    ['name' => 'Statistik Usaha Restoran', 'href' => url('/statistik/usaha-restoran')],
                    ['name' => 'Statistik Jasa Perjalanan', 'href' => url('/statistik/jasa-perjalanan')],
                    ['name' => 'Statistik Satelit Pariwisata', 'href' => url('/statistik/satelit-pariwisata')],
                    ['name' => 'Statistik Devisa Pariwisata', 'href' => url('/statistik/devisa-pariwisata')],
                ]
            ],
            ['name' => 'Statistik Ekonomi Kreatif', 'href' => url('/statistik/ekonomi-kreatif')],
        ]
    ],
    [ 
        'name' => 'Informasi Agenda Dinas Pariwisata dan Ekonomi Kreatif', 
        'href' => url('/agenda') 
    ],
];
@endphp

<!-- Top Header with Logo & Search (Full Width) -->
<div class="w-full bg-white border-b border-slate-200 py-3 sm:py-4 px-4 sm:px-10">
    <div class="w-full flex flex-col items-stretch gap-4 sm:flex-row sm:items-center sm:justify-between">
        <!-- Brand Logo & Title -->
        <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-3.5">
            <img 
                src="{{ $siteSetting?->logo_dinas ? asset('storage/' . $siteSetting->logo_dinas) : asset('images/logodisparmerauke.webp') }}" 
                alt="Logo Dinas Pariwisata" 
                class="h-12 sm:h-16 w-auto shrink-0 object-contain"
            />
            <div class="min-w-0">
                <h1 class="break-words text-sm sm:text-lg font-bold text-slate-900 leading-tight">
                    {{ $siteSetting?->nama_instansi ?? 'Dinas Pariwisata dan Ekonomi Kreatif' }}
                </h1>
                <p class="break-words text-xs sm:text-sm font-semibold text-slate-700">
                    {{ $siteSetting?->nama_daerah ?? 'Kabupaten Merauke' }}
                </p>
            </div>
        </a>

        <!-- Top Right: Logo Pariwisata & Search Bar -->
        <div class="flex w-full items-center gap-4 sm:w-auto sm:gap-6">
            <img 
                src="{{ $siteSetting?->logo_pariwisata ? asset('storage/' . $siteSetting->logo_pariwisata) : asset('images/logodispar.webp') }}" 
                alt="Logo Pariwisata" 
                class="h-12 sm:h-14 w-auto hidden sm:block object-contain"
            />
            
            <form action="{{ url('/pencarian') }}" method="GET" class="flex w-full items-center gap-2.5 sm:w-auto">
                <div class="relative flex min-w-0 flex-1 items-center sm:flex-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>

                    <input 
                        type="text" 
                        name="q" 
                        value="{{ request('q') }}"
                        placeholder="Cari..." 
                        class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-[#0066b2] focus:outline-none focus:ring-1 focus:ring-[#0066b2] sm:w-64"
                    />
                </div>

                <button 
                    type="submit" 
                    class="flex shrink-0 items-center justify-center rounded-lg bg-[#1565c0] p-2.5 text-white shadow-sm transition-colors hover:bg-[#0d47a1]"
                    aria-label="Cari"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Main Navigation Bar -->
<nav x-data="{ mobileOpen: false }" class="w-full bg-[#0066b2] text-white text-sm font-semibold shadow-md px-4 sm:px-8 z-50 relative">
    <div class="sm:hidden w-full flex items-center justify-between min-h-14">
        <span class="sm:hidden text-xs uppercase tracking-wider text-white/80">Menu Navigasi</span>
        <button @click="mobileOpen = !mobileOpen" type="button" class="sm:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-[#005596] focus:outline-none focus:ring-2 focus:ring-white/70" :aria-expanded="mobileOpen" aria-controls="main-navigation" aria-label="Buka menu navigasi">
            <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
            <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <div id="main-navigation" x-show="mobileOpen" x-transition x-cloak class="sm:hidden border-t border-white/20 pb-3">
        @foreach($navItems as $item)
            @php $hasChildren = !empty($item['children']); @endphp
            <div x-data="{ open: false }" class="border-b border-white/10 last:border-0">
                @if($hasChildren)
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-2 py-3 text-left hover:bg-[#005596] rounded-lg" :aria-expanded="open">
                        <span>{{ $item['name'] }}</span><svg class="w-4 h-4 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" /></svg>
                    </button>
                    <div x-show="open" x-transition class="pl-3 pb-2 space-y-1">
                        @foreach($item['children'] as $subItem)
                            @php $hasSubChildren = !empty($subItem['children']); @endphp
                            <div x-data="{ open: false }">
                                @if($hasSubChildren)
                                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3 py-2.5 text-left text-xs text-white/90 hover:bg-[#005596] rounded-lg" :aria-expanded="open"><span>{{ $subItem['name'] }}</span><svg class="w-3.5 h-3.5 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" /></svg></button>
                                    <div x-show="open" x-transition class="pl-3 space-y-1">
                                        @foreach($subItem['children'] as $leafItem)
                                            @php $hasLeafChildren = !empty($leafItem['children']); @endphp
                                            <div @if($hasLeafChildren) x-data="{ open: false }" @endif>
                                                @if($hasLeafChildren)
                                                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3 py-2.5 text-left text-xs text-white/90 hover:bg-[#005596] rounded-lg" :aria-expanded="open">
                                                        <span>{{ $leafItem['name'] }}</span>
                                                        <svg class="w-3.5 h-3.5 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" /></svg>
                                                    </button>
                                                    <div x-show="open" x-transition class="pl-3 space-y-1">
                                                        @foreach($leafItem['children'] as $nestedItem)
                                                            <a href="{{ $nestedItem['href'] }}" class="block px-3 py-2 text-xs text-white/80 hover:text-white hover:bg-[#005596] rounded-lg">{{ $nestedItem['name'] }}</a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <a href="{{ $leafItem['href'] }}" class="block px-3 py-2 text-xs text-white/80 hover:text-white hover:bg-[#005596] rounded-lg">{{ $leafItem['name'] }}</a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <a href="{{ $subItem['href'] }}" class="block px-3 py-2.5 text-xs text-white/90 hover:bg-[#005596] rounded-lg">{{ $subItem['name'] }}</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <a href="{{ $item['href'] }}" class="block px-2 py-3 hover:bg-[#005596] rounded-lg">{{ $item['name'] }}</a>
                @endif
            </div>
        @endforeach
    </div>

    <div class="hidden sm:flex w-full items-center">
        @foreach($navItems as $index => $item)
            @php $hasLevel1Children = !empty($item['children']); @endphp
            <div class="relative group/level1">
                <a href="{{ $item['href'] }}" class="px-5 py-3.5 hover:bg-[#005596] transition-colors flex items-center gap-1.5 {{ request()->is(trim($item['href'], '/')) ? 'bg-[#005596]' : '' }}">
                    <span>{{ $item['name'] }}</span>
                    @if($hasLevel1Children)
                        <svg class="w-3.5 h-3.5 text-white/80 group-hover/level1:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    @endif
                </a>
                @if($hasLevel1Children)
                    <div class="absolute left-0 top-full hidden group-hover/level1:block w-64 bg-white text-slate-800 shadow-xl border border-slate-200 py-2 z-50 rounded-b-lg">
                        @foreach($item['children'] as $subItem)
                            @php $hasLevel2Children = !empty($subItem['children']); @endphp
                            <div class="relative group/level2">
                                <a href="{{ $subItem['href'] }}" class="flex items-center justify-between px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-[#0066b2] transition-colors border-b border-slate-100 last:border-none">
                                    <span>{{ $subItem['name'] }}</span>
                                    @if($hasLevel2Children)
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover/level2:text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                    @endif
                                </a>
                                @if($hasLevel2Children)
                                    <div class="absolute left-full top-0 hidden group-hover/level2:block w-64 bg-white text-slate-800 shadow-xl border border-slate-200 py-2 z-50 rounded-r-lg">
                                        @foreach($subItem['children'] as $subSubItem)
                                            @php $hasLevel3Children = !empty($subSubItem['children']); @endphp
                                            <div class="relative group/level3">
                                                <a href="{{ $subSubItem['href'] }}" class="flex items-center justify-between px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-[#0066b2] transition-colors border-b border-slate-100 last:border-none">
                                                    <span>{{ $subSubItem['name'] }}</span>
                                                    @if($hasLevel3Children)
                                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover/level3:text-[#0066b2]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                                    @endif
                                                </a>
                                                @if($hasLevel3Children)
                                                    <div class="absolute left-full top-0 hidden group-hover/level3:block w-60 bg-white text-slate-800 shadow-xl border border-slate-200 py-2 z-50 rounded-r-lg">
                                                        @foreach($subSubItem['children'] as $leafItem)
                                                            <a href="{{ $leafItem['href'] }}" class="block px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-[#0066b2] transition-colors border-b border-slate-100 last:border-none">{{ $leafItem['name'] }}</a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</nav>