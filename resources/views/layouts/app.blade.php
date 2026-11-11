<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="{{ asset('images/logodisparmerauke.webp') }}">
    <title>@yield('title', 'Dinas Pariwisata dan Ekonomi Kreatif Kab. Merauke')</title>
    
    {{-- Tailwind CSS & JS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Alpine.js for interactivity --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased flex flex-col min-h-screen">

    {{-- Partial Header --}}
    @include('partials.header')

    {{-- Main Content Slot --}}
    <main class="grow">
        @yield('content')
    </main>

    {{-- Partial Footer --}}
    @include('partials.footer')

    @stack('scripts')
</body>
</html>