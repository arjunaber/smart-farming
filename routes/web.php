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


Route::get('/', function () { return redirect('/dashboard'); });
Route::get('/dashboard', function () { return view('dashboard'); });
Route::get('/disease', function () { return view('disease'); });
Route::get('/growth', function () { return view('growth'); });
Route::get('/chatbot', function () { return view('chatbot'); });
Route::get('/reports', function () { return view('reports'); });
Route::get('/settings', function () { return view('settings'); });
