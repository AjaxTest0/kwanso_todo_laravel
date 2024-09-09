<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
// use App\Http\Middleware\CheckAdmin;



Route::get('/', function () {
    return redirect('/login');
});

// LOGIN
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register-form');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
