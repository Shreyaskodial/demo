<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

/*
|----------------------------------------------------------------------
| Public routes
|----------------------------------------------------------------------
*/
Route::view('/', 'welcome');

// Auth (login / logout)
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration
Route::get('/register',  [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

/*
|----------------------------------------------------------------------
| Auth‑protected routes
|----------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    /*
    |------------------------------------------------------------------
    | Users (soft‑delete via is_deleted column)
    |------------------------------------------------------------------
    */

    // ✅ ONE canonical route for bulk soft‑delete (checkbox form)
    Route::delete('/users/bulk-soft-delete', [UserController::class, 'bulkSoftDelete'])
         ->name('users.bulk-soft-delete');

    // 🔄 Restore a single soft‑deleted user
    Route::patch('/users/{user}/restore', [UserController::class, 'restore'])
         ->withTrashed()               // include trashed model in binding
         ->name('users.restore');

    // Optional separate bulk‑delete alias (uncomment if still needed)
    // Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])
    //      ->name('users.bulk-delete');

    // Resource routes we need (index, edit, update, destroy)
    Route::resource('users', UserController::class)
         ->only(['index', 'edit', 'update', 'destroy']);
});
Route::delete('/users/{user}/softdelete', [UserController::class, 'destroy'])
    ->name('users.softdelete');
    