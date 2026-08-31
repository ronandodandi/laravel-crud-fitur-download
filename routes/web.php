<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('barangs', BarangController:: class);
Route::get('/barangs/{id}/download', [BarangController::class, 'download'])->name('barangs.download');
Route::get('/barangs-export', [BarangController::class, 'export'])->name('barangs.export');