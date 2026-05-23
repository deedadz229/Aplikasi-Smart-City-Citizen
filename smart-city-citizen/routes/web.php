<?php

use App\Models\Warga;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index', [
        'totalWarga' => Warga::count(),
        'wargaTerbaru' => Warga::latest()->take(5)->get(),
    ]);
});

Route::get('/warga', function () {
    return view('warga.index');
});
