<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Actions\TokenManagementAction;
use App\Actions\RegisterTokenAction;
use App\Actions\LoginAction;

// use App\Http\Middleware\CheckAdmin;


// Show registration form and handle registration


Route::get('/', function () {
    return redirect('/login');
});

Route::match(['get', 'post'], '/login', [LoginAction::class, 'handle'])->name('login');
Route::match(['get', 'post'], '/register', [RegisterTokenAction::class, 'handle'])->name('register');
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

    // Laravel action
    Route::match(['get', 'post'], '/generate', [TokenManagementAction::class, 'handle'])->name('generate');


});
