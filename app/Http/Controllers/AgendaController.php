<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Category;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Agenda::with(['category', 'author'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // Filter Pencarian Kata Kunci Judul
        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        // Filter Berdasarkan Tahun (Published At / Event Date)
        if ($request->filled('tahun')) {
            $query->where(function ($q) use ($request) {
                $q->whereYear('published_at', $request->tahun)
                    ->orWhereYear('event_date', $request->tahun);
            });
        }

        $agendas = $query->paginate(9)->withQueryString();
        $categories = Category::where('type', 'agenda')->get();

        return view('publikasi.agenda.index', compact('agendas', 'categories'));
    }

    public function show($slug)
    {
        $agenda = Agenda::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedAgendas = Agenda::with('category')
            ->where('id', '!=', $agenda->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('publikasi.agenda.show', compact('agenda', 'relatedAgendas'));
    }
}
