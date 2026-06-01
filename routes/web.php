<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SiklusTanamController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\ChatbotController;

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

    // Route Siklus Tanam
    Route::resource('siklus-tanam', SiklusTanamController::class)->names('siklus-tanam');

    // 2. TAMBAHKAN ROUTE KEUANGAN DI SINI (WAJIB)
    Route::resource('keuangan', KeuanganController::class);

    // Route Khusus Super Admin
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/lahan', [AdminController::class, 'lahan'])->name('admin.lahan');
        Route::get('/rag', function () {
            return view('rag.index'); // Pastikan file blade-nya ada di resources/views/rag/index.blade.php
        })->name('rag.index');

        // Route dummy untuk simulasi upload form (akan diurus BE nanti)
        Route::post('/rag/upload', function () {
            return back()->with('success', 'File RAG berhasil diproses (Simulasi UI)');
        });
        Route::get('/settings', function () {
            return view('settings');
        })->name('settings');
    });

    // Route Halaman FE Lainnya
    Route::get('/disease', function () {
        return view('disease');
    })->name('disease');

    Route::prefix('chatbot')->middleware('auth')->group(function () {
        Route::get('/', [ChatbotController::class, 'index'])->name('chatbot.index');
        Route::get('/history', [ChatbotController::class, 'history'])->name('chatbot.history');
        Route::get('/history/{sessionId}', [ChatbotController::class, 'historyDetail'])->name('chatbot.history.detail');
        Route::post('/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
    });

    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');
});
