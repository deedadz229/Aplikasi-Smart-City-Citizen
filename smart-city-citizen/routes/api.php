<?php

use App\Http\Controllers\Api\WargaController;
use App\Http\Controllers\Api\BeritaKotaController;
use App\Http\Controllers\Api\InstansiController;
use App\Http\Controllers\Api\PetugasController;
use Illuminate\Support\Facades\Route;

Route::apiResource('warga', WargaController::class);
Route::apiResource('berita-kota', BeritaKotaController::class);
Route::apiResource('instansi', InstansiController::class);
Route::apiResource('petugas', PetugasController::class);
