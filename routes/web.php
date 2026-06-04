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
use App\Http\Controllers\RagDocumentController;

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

    Route::resource('siklus-tanam', SiklusTanamController::class)->names('siklus-tanam');
    Route::resource('keuangan', KeuanganController::class);

    // Route Khusus Super Admin
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/lahan', [AdminController::class, 'lahan'])->name('admin.lahan');

        Route::controller(RagDocumentController::class)->prefix('rag')->name('rag.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/upload', 'upload')->name('upload');
            Route::delete('/{ragDocument}', 'destroy')->name('destroy');
        });

        Route::controller(\App\Http\Controllers\Admin\DeviceController::class)->prefix('admin/iot-devices')->name('admin.iot-devices.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/pending', 'pending')->name('pending');
            Route::post('/{device}/approve', 'approve')->name('approve');
            Route::post('/{device}/suspend', 'suspend')->name('suspend');
            Route::post('/{device}/reset', 'reset')->name('reset');
        });

        Route::get('/settings', function () {
            return view('settings');
        })->name('settings');
    });

    // Route Halaman FE Lainnya
    Route::get('/disease', function () {
        return view('disease');
    })->name('disease');

    Route::prefix('chatbot')->group(function () {
        Route::get('/', [ChatbotController::class, 'index'])->name('chatbot.index');
        Route::get('/history', [ChatbotController::class, 'history'])->name('chatbot.history');
        Route::get('/history/{sessionId}', [ChatbotController::class, 'historyDetail'])->name('chatbot.history.detail');
        Route::post('/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');
    });

    Route::get('/reports', function () {
        return view('reports');
    })->name('reports');
});
