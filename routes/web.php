<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;



Route::get('/', function () {
    return redirect('/login');
});

// LOGIN
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register-form');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// TODOS

// Route::middleware(['admin'])->group(function () {

    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::get('todos/{todo}', [TodoController::class, 'destroy'])->name('todos.delete');

    // GENERATE
    Route::get('/generate', [AuthController::class, 'generateTokenView'])->name('generate');
    Route::post('generate-token', [AuthController::class, 'generateToken'])->name('generate-token');
// });

