<?php

use App\Http\Controllers\ExportImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Home::class)->name('home');
Route::get('/export-soal', [ExportImportController::class, 'exportSoal'])->name('export-soal');
Route::get('/export-dosen', [ExportImportController::class, 'exportDosen'])->name('export-dosen');
Route::get('/export-mahasiswa', [ExportImportController::class, 'exportMahasiswa'])->name('export-mahasiswa');