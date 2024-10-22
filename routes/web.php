<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\{MainController, CarController,
    ModelController, MarkController};

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'index')->name('index');
});

Route::controller(CarController::class)->group(function () {
    Route::get('/catalog/{id}', 'index')->name('catalog.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(IsAdmin::class)->group(function () {
    Route::controller(MarkController::class)->group(function () {
        Route::get('/admin/marks', 'index')->name('mark.index');
        Route::post('/admin/marks', 'upload')->name('mark.upload');
        Route::delete('/admin/marks', 'destroy')->name('mark.destroy');
        Route::patch('/admin/marks', 'update')->name('mark.update');
        Route::post('/admin/marks/search', 'search')->name('mark.search');
    });
    Route::controller(ModelController::class)->group(function () {
        Route::get('/admin/models', 'index')->name('model.index');
        Route::post('/admin/models', 'upload')->name('model.upload');
        Route::delete('/admin/models', 'destroy')->name('model.destroy');
        Route::post('/admin/models/search', 'search')->name('model.search');
    });
});



require __DIR__.'/auth.php';
