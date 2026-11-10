<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\HeroSlide;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil slider beranda yang aktif
        $slides = HeroSlide::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        // Ambil 3 agenda/berita publikasi terbaru
        $items = Agenda::where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('home', compact('slides', 'items'));
    }
}
