<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_instansi',
        'nama_daerah',
        'logo_pariwisata',
        'logo_dinas',
        'alamat',
        'telepon',
        'email',
        'instagram',
        'facebook',
        'youtube',
        'tiktok',
        'logo_title',        // <-- Tambahkan ini
        'logo_subtitle',     // <-- Tambahkan ini
        'logo_description',  // <-- Tambahkan ini
    ];
}
