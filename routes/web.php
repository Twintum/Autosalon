<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\{MainController, OrderController,
    ModelController, MarkController, AdminController};

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/model/{id}', 'product')->name('product');
    Route::post('/filter}', 'filter')->name('filter');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::controller(OrderController::class)->group(function () {
        Route::get('/order', 'index')->name('order.index');
        Route::get('/admin/order', 'admin')->middleware(IsAdmin::class)->name('admin.order.index');
        Route::patch('/admin/order', 'delivered')->middleware(IsAdmin::class)->name('order.delivered');
        Route::post('/order', 'upload')->name('order.upload');
        Route::delete('/order', 'destroy')->name('order.destroy');
    });
});

Route::middleware(IsAdmin::class)->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.exel');

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
        Route::post('/admin/models/search', 'search')->name('model.search');
        Route::delete('/admin/models', 'destroy')->name('model.destroy');
        Route::patch('/admin/models', 'update')->name('model.update');
    });
});



require __DIR__.'/auth.php';
