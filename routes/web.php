<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SiklusTanamController; // 1. Pastikan Controller Ini Di-import

Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistration'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/sensor', [SensorController::class, 'store']);

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('lahan', LahanController::class);
    Route::resource('logbook', LogbookController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::put('/lahan/{id}/update-polygon', [LahanController::class, 'updatePolygon'])->name('lahan.update-polygon');

    // 2. Tambahkan ini agar rute 'siklus-tanam.create' terdefinisi dengan benar di aplikasi
    Route::resource('siklus-tanam', SiklusTanamController::class)->names('siklus-tanam');

    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/lahan', [AdminController::class, 'lahan'])->name('admin.lahan');
    });

    Route::get('/disease', function () {
        return view('disease');
    })->name('disease');

    Route::get('/chatbot', function () {
        return view('chatbot');
    })->name('chatbot');

    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
});
