<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaKota extends Model
{
    protected $table = 'berita_kota';

    protected $fillable = [
        'judul',
        'isi',
        'kategori',
        'penulis',
        'tanggal_terbit',
    ];
}