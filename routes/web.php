<?php

use Illuminate\Support\Facades\Route;

// Route untuk halaman Dashboard utama
Route::get('/', function () {
    return view('dashboard'); 
})->name('dashboard');

// Contoh route untuk halaman lain (Disease Classification)
Route::get('/disease', function () {
    return view('disease'); 
})->name('disease');