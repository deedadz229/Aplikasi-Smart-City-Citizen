<?php

use App\Http\Controllers\Api\WargaController;
use App\Http\Controllers\Api\BeritaKotaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('warga', WargaController::class);
Route::apiResource('berita-kota', BeritaKotaController::class);