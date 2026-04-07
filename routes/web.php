<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/disease', function () {
    return view('disease');
});
Route::get('/growth', function () {
    return view('growth');
});
Route::get('/chatbot', function () {
    return view('chatbot');
});
Route::get('/reports', function () {
    return view('reports');
});
Route::get('/settings', function () {
    return view('settings');
});


Route::get('/growth', [TanamanController::class, 'index'])->name('growth');
Route::post('/growth', [TanamanController::class, 'store'])->name('tanaman.store');
Route::put('/tanaman/{id}', [TanamanController::class, 'update'])->name('tanaman.update');
Route::delete('/tanaman/{id}', [TanamanController::class, 'destroy'])->name('tanaman.destroy');
