<?php

use App\Models\Warga;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index', [
        'totalWarga' => Warga::count(),
        'wargaTerbaru' => Warga::latest()->take(5)->get(),
        'totalPetugas' => Petugas::count(),
        'petugasTerbaru' => Petugas::latest()->take(5)->get(),
    ]);
});

Route::get('/warga', function () {
    return view('warga.index');
});

Route::get('/petugas', function () {
    return view('petugas.index');
});

Route::get('/berita-kota', function () {
    return view('berita-kota.index');
});

Route::get('/laporan', function (Request $request) {
    $query = Warga::query();

    if ($request->filled('keyword')) {
        $keyword = $request->keyword;

        $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'like', "%{$keyword}%")
                ->orWhere('nik', 'like', "%{$keyword}%")
                ->orWhere('alamat', 'like', "%{$keyword}%")
                ->orWhere('no_hp', 'like', "%{$keyword}%");
        });
    }

    if ($request->filled('tanggal_awal')) {
        $query->whereDate('created_at', '>=', $request->tanggal_awal);
    }

    if ($request->filled('tanggal_akhir')) {
        $query->whereDate('created_at', '<=', $request->tanggal_akhir);
    }

    $warga = $query->latest()->get();

    return view('laporan.index', [
        'warga' => $warga,
        'totalWarga' => $warga->count(),
        'keyword' => $request->keyword,
        'tanggalAwal' => $request->tanggal_awal,
        'tanggalAkhir' => $request->tanggal_akhir,
    ]);
}); 