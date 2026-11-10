<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Statistic\CreativeEconomyController;
// Single Responsibility Statistic Controllers
use App\Http\Controllers\Statistic\ForeignTouristController;
use App\Http\Controllers\Statistic\HotelStatisticController;
use App\Http\Controllers\Statistic\NationalTouristController;
use App\Http\Controllers\Statistic\NusantaraTouristController;
use App\Http\Controllers\Statistic\RestaurantDirectoryController;
use App\Http\Controllers\Statistic\RestaurantStatisticController;
use App\Http\Controllers\Statistic\TourismForeignExchangeController;
use App\Http\Controllers\Statistic\TourismSatelliteController;
use App\Http\Controllers\Statistic\TourismServiceController;
use Illuminate\Support\Facades\Route;

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');

// Peta Sebaran Wilayah
Route::get('/publikasi/peta-sebaran-wilayah', [MapController::class, 'index'])->name('map.index');
Route::get('/publikasi/peta-sebaran', [MapController::class, 'index']); // Alias Route Peta

// Profil & Halaman Statis
Route::get('/profil-lembaga', [PageController::class, 'profilLembaga'])->name('profil.lembaga');
Route::get('/struktur-organisasi', [PageController::class, 'strukturOrganisasi'])->name('profil.struktur');
Route::get('/pencarian', [SearchController::class, 'index'])->name('search');

// Modul Statistik (Memakai Dedicated Invokable Controllers)
Route::prefix('statistik')->name('statistik.')->group(function () {
    Route::get('/ekonomi-kreatif', CreativeEconomyController::class)->name('ekraf');
    Route::get('/akomodasi/hotel-dan-penginapan', HotelStatisticController::class)->name('hotel');

    // 1. Tabel Direktori Restoran & Kuliner
    Route::get('/akomodasi/restoran', RestaurantDirectoryController::class)->name('restoran');

    // 2. Grafik Statistik Tren Usaha Restoran
    Route::get('/usaha-restoran', RestaurantStatisticController::class)->name('usaha-restoran');

    Route::get('/wisatawan-nusantara', NusantaraTouristController::class)->name('wisatawan');
    Route::get('/wisatawan-mancanegara', ForeignTouristController::class)->name('mancanegara');
    Route::get('/wisatawan-nasional', NationalTouristController::class)->name('nasional');
    Route::get('/jasa-perjalanan', TourismServiceController::class)->name('jasa-perjalanan');
    Route::get('/satelit-pariwisata', TourismSatelliteController::class)->name('satelit-pariwisata');
    Route::get('/devisa-pariwisata', TourismForeignExchangeController::class)->name('devisa-pariwisata');
});

// Ragam Wisata
Route::get('/publikasi/ragam-wisata', [DestinationController::class, 'wisata'])->name('wisata.index');
Route::get('/publikasi/ragam-wisata/{slug}', [DestinationController::class, 'showWisata'])->name('wisata.show');

// Ragam Ekonomi Kreatif
Route::get('/publikasi/ragam-ekonomi-kreatif', [DestinationController::class, 'ekraf'])->name('ekraf.index');
Route::get('/publikasi/ragam-ekonomi-kreatif/{slug}', [DestinationController::class, 'showEkraf'])->name('ekraf.show');

// Agenda & Berita
Route::get('/publikasi/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/publikasi/agenda/{slug}', [AgendaController::class, 'show'])->name('agenda.show');
Route::get('/agenda', [AgendaController::class, 'index']); // Alias Route Agenda
Route::get('/agenda/{slug}', [AgendaController::class, 'show']); // Alias Route Detail Agenda
