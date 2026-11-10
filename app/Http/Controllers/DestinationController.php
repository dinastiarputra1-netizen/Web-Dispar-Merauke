<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        return $this->wisata($request);
    }

    // Katalog Ragam Wisata
    public function wisata(Request $request)
    {
        $categories = Category::where('type', 'wisata')->get();

        $query = Destination::with('category')
            ->where('type', 'wisata')
            ->where('status', 'published');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('location_name', 'like', '%'.$request->q.'%')
                    ->orWhere('district', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('distrik')) {
            $query->where('district', $request->distrik);
        }

        $destinations = $query->latest('published_at')->paginate(9)->withQueryString();

        $districts = Destination::where('type', 'wisata')
            ->whereNotNull('district')
            ->pluck('district')
            ->unique();

        return view('publikasi.ragam-wisata', compact('destinations', 'categories', 'districts'));
    }

    // Detail Ragam Wisata
    public function showWisata($slug)
    {
        $destination = Destination::with(['category', 'author'])
            ->where('type', 'wisata')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedDestinations = Destination::where('type', 'wisata')
            ->where('status', 'published')
            ->where('id', '!=', $destination->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('publikasi.detail-wisata', compact('destination', 'relatedDestinations'));
    }

    // Katalog Ragam Ekonomi Kreatif
    public function ekraf(Request $request)
    {
        $categories = Category::where('type', 'ekraf')->get();

        $query = Destination::with('category')
            ->where('type', 'ekraf')
            ->where('status', 'published');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('location_name', 'like', '%'.$request->q.'%')
                    ->orWhere('district', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('distrik')) {
            $query->where('district', $request->distrik);
        }

        $ekrafs = $query->latest('published_at')->paginate(9)->withQueryString();

        $districts = Destination::where('type', 'ekraf')
            ->whereNotNull('district')
            ->pluck('district')
            ->unique();

        return view('publikasi.ragam-ekonomi', compact('ekrafs', 'categories', 'districts'));
    }

    // Detail Ragam Ekonomi Kreatif
    public function showEkraf($slug)
    {
        $ekraf = Destination::with(['category', 'author'])
            ->where('type', 'ekraf')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedEkrafs = Destination::where('type', 'ekraf')
            ->where('status', 'published')
            ->where('id', '!=', $ekraf->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('publikasi.detail-ekonomi', compact('ekraf', 'relatedEkrafs'));
    }
}
