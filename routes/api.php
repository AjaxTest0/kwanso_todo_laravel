<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;

Route::middleware('auth')->group(function () {

    // TODOS Routes
    Route::prefix('todos')->name('todos.')->group(function () {
        Route::get('/', [TodoController::class, 'index'])->name('index');
        Route::get('/create', [TodoController::class, 'create'])->name('create');
        Route::post('/', [TodoController::class, 'store'])->name('store');
        Route::get('/{todo}/edit', [TodoController::class, 'edit'])->name('edit');
        Route::put('/{todo}', [TodoController::class, 'update'])->name('update');
        Route::get('/{todo}', [TodoController::class, 'destroy'])->name('delete');
    });

    // GENERATE Routes
    Route::prefix('generate')->name('generate.')->group(function () {
        Route::get('/', [AuthController::class, 'generateTokenView'])->name('view');
        Route::post('/token', [AuthController::class, 'generateToken'])->name('token');
    });

});

?>
