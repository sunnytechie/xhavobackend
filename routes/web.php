<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard.index');

    // Users
    Route::get('/users/providers', [App\Http\Controllers\Dashboard\UserController::class, 'provider'])->name('users.provider');
    Route::get('/users/customers', [App\Http\Controllers\Dashboard\UserController::class, 'customer'])->name('users.customer');

    // bookings
    Route::get('/bookings', [App\Http\Controllers\Dashboard\BookingController::class, 'index'])->name('bookings.index');

    // pages
    Route::get('/pages', [App\Http\Controllers\Dashboard\PageController::class, 'index'])->name('pages.index');
    
    // Categories resource
    Route::resource('categories', App\Http\Controllers\Dashboard\CategoryController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
