<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\{MainController, CarController,
    AdminController};

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
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});



require __DIR__.'/auth.php';
