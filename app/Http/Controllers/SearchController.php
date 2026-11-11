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
                'image' => $destination->main_image ? asset('storage/' . $destination->main_image) : null,
                'url' => $destination->type === 'ekraf' ? route('ekraf.show', $destination->slug) : route('wisata.show', $destination->slug),
            ])->toArray();

            $agendaResults = Agenda::query()->where('status', 'published')->where(function ($query) use ($term): void {
                $query->where('title', 'like', $term)->orWhere('content', 'like', $term);
            })->get()->map(fn (Agenda $agenda): array => [
                'type' => 'Agenda & Berita',
                'title' => $agenda->title,
                'description' => $agenda->event_date ? $agenda->event_date->translatedFormat('d F Y') : '',
                'image' => $agenda->cover_image ? asset('storage/' . $agenda->cover_image) : null,
                'url' => route('agenda.show', $agenda->slug),
            ])->toArray();

            $pageResults = Page::query()->where(function ($query) use ($term): void {
                $query->where('title', 'like', $term)->orWhere('content', 'like', $term);
            })->get()->map(fn (Page $page): array => [
                'type' => 'Halaman', 
                'title' => $page->title, 
                'description' => 'Informasi situs', 
                'image' => null,
                'url' => url('/'.$page->slug),
            ])->toArray();

            $hotelResults = Hotel::query()->where('is_active', true)->where(function ($query) use ($term): void {
                $query->where('name', 'like', $term)->orWhere('address', 'like', $term)->orWhere('classification', 'like', $term);
            })->get()->map(fn (Hotel $hotel): array => [
                'type' => 'Hotel & Penginapan', 
                'title' => $hotel->name, 
                'description' => $hotel->address, 
                'image' => $hotel->image ? asset('storage/' . $hotel->image) : null,
                'url' => route('statistik.hotel'),
            ])->toArray();

            $restaurantResults = Restaurant::query()->where('is_active', true)->where(function ($query) use ($term): void {
                $query->where('business_name', 'like', $term)->orWhere('address', 'like', $term)->orWhere('business_type', 'like', $term);
            })->get()->map(fn (Restaurant $restaurant): array => [
                'type' => 'Restoran & Kuliner', 
                'title' => $restaurant->business_name, 
                'description' => $restaurant->address, 
                'image' => $restaurant->image ? asset('storage/' . $restaurant->image) : null,
                'url' => route('statistik.restoran'),
            ])->toArray();

            $results = collect(array_merge(
                $results,
                $agendaResults,
                $pageResults,
                $hotelResults,
                $restaurantResults
            ));
        }

        return view('search.index', compact('search', 'results'));
    }
}
