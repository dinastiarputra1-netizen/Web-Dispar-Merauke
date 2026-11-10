<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function profilLembaga()
    {
        $page = Page::where('slug', 'profil-lembaga')->first();

        // Fallback jika data di database belum diisi melalui admin
        if (! $page) {
            $page = (object) [
                'title' => 'Profil Lembaga',
                'featured_image' => null,
                'content' => '<p>Dinas Pariwisata dan Ekonomi Kreatif Kabupaten Merauke merupakan unsur pelaksana urusan pemerintahan di bidang pariwisata dan ekonomi kreatif yang menjadi kewenangan daerah.</p>',
            ];
        }

        return view('profil.lembaga', compact('page'));
    }

    public function strukturOrganisasi()
    {
        $page = Page::where('slug', 'struktur-organisasi')->first();

        // Fallback jika data di database belum diisi melalui admin
        if (! $page) {
            $page = (object) [
                'title' => 'Struktur Organisasi',
                'featured_image' => null,
                'content' => null,
            ];
        }

        return view('profil.struktur-organisasi', compact('page'));
    }
}
