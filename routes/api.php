<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RagDocumentController;


Route::middleware('rag.service')->prefix('rag')->name('api.rag.')->group(function () {

    Route::controller(RagDocumentController::class)->prefix('documents')->name('documents.')->group(function () {
        Route::get('/pending', 'apiPending')->name('pending');
        Route::get('/', 'apiIndex')->name('index');
        Route::post('/',  'apiStore')->name('store');
        Route::get('/{ragDocument}',  'apiShow')->name('show');
        Route::delete('/{ragDocument}', 'apiDestroy')->name('destroy');
        Route::get('/{ragDocument}/download', 'apiDownload')->name('download');
        Route::patch('/{ragDocument}/status', 'apiUpdateStatus')->name('update-status');
    });
});