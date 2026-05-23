<?php

use App\Http\Controllers\Api\WargaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('warga', WargaController::class);
