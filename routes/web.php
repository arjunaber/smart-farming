 
<?php

use Illuminate\Support\Facades\Route;
<<<<<<< Updated upstream

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
=======
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistration'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('lahan', LahanController::class);
    Route::resource('logbook', LogbookController::class)->only(['index', 'create', 'store', 'destroy']);
    
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/lahan', [AdminController::class, 'lahan'])->name('admin.lahan');
    });

    Route::get('/disease', function () {
        return view('disease');
    })->name('disease');

    Route::get('/growth', [TanamanController::class, 'index'])->name('growth');
    Route::post('/growth', [TanamanController::class, 'store'])->name('tanaman.store');
    Route::put('/tanaman/{id}', [TanamanController::class, 'update'])->name('tanaman.update');
    Route::delete('/tanaman/{id}', [TanamanController::class, 'destroy'])->name('tanaman.destroy');

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

>>>>>>> Stashed changes
