<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return view('welcome');
});

Route::get('/dashboard', function () {
	return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {

	Route::resource('users', UserController::class);

	// Superadmin password reset route
	Route::get('users/{user}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('users.resetPassword');
	Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword.post');

	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
