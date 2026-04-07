<?php

use Illuminate\Support\Facades\Route;
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
