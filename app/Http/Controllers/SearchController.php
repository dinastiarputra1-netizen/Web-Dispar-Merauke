<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Page;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $results = collect();

        if ($search !== '') {
            $term = '%'.$search.'%';

            $results = Destination::query()->where('status', 'published')->where(function ($query) use ($term): void {
                $query->where('title', 'like', $term)->orWhere('location_name', 'like', $term)->orWhere('district', 'like', $term);
            })->get()->map(fn (Destination $destination): array => [
                'type' => $destination->type === 'ekraf' ? 'Ekonomi Kreatif' : 'Destinasi Wisata',
                'title' => $destination->title,
                'description' => $destination->location_name ?: $destination->district,
                'url' => $destination->type === 'ekraf' ? route('ekraf.show', $destination->slug) : route('wisata.show', $destination->slug),
            ]);

            $results = $results->merge(Agenda::query()->where('status', 'published')->where(function ($query) use ($term): void {
                $query->where('title', 'like', $term)->orWhere('content', 'like', $term);
            })->get()->map(fn (Agenda $agenda): array => [
                'type' => 'Agenda & Berita',
                'title' => $agenda->title,
                'description' => $agenda->event_date?->translatedFormat('d F Y'),
                'url' => route('agenda.show', $agenda->slug),
            ]));

            $results = $results->merge(Page::query()->where(function ($query) use ($term): void {
                $query->where('title', 'like', $term)->orWhere('content', 'like', $term);
            })->get()->map(fn (Page $page): array => [
                'type' => 'Halaman', 'title' => $page->title, 'description' => 'Informasi situs', 'url' => url('/'.$page->slug),
            ]));

            $results = $results->merge(Hotel::query()->where('is_active', true)->where(function ($query) use ($term): void {
                $query->where('name', 'like', $term)->orWhere('address', 'like', $term)->orWhere('classification', 'like', $term);
            })->get()->map(fn (Hotel $hotel): array => [
                'type' => 'Hotel & Penginapan', 'title' => $hotel->name, 'description' => $hotel->address, 'url' => route('statistik.hotel'),
            ]));

            $results = $results->merge(Restaurant::query()->where('is_active', true)->where(function ($query) use ($term): void {
                $query->where('business_name', 'like', $term)->orWhere('address', 'like', $term)->orWhere('business_type', 'like', $term);
            })->get()->map(fn (Restaurant $restaurant): array => [
                'type' => 'Restoran & Kuliner', 'title' => $restaurant->business_name, 'description' => $restaurant->address, 'url' => route('statistik.restoran'),
            ]));
        }

        return view('search.index', compact('search', 'results'));
    }
}
