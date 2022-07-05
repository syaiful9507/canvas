<?php

declare(strict_types=1);

use Canvas\Http\Controllers\Auth\AuthenticatedSessionController;
use Canvas\Http\Controllers\Auth\NewPasswordController;
use Canvas\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->group(function () {
    // Login routes...
    Route::prefix('login')->group(function () {
        Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('canvas.login.view');
        Route::post('/', [AuthenticatedSessionController::class, 'store'])->name('canvas.login');
    });

    // Forgot password routes...
    Route::prefix('forgot-password')->group(function () {
        Route::get('/', [PasswordResetLinkController::class, 'create'])->name('canvas.forgot-password.view');
        Route::post('/', [PasswordResetLinkController::class, 'store'])->name('canvas.forgot-password');
    });

    // Reset password routes...
    Route::prefix('reset-password')->group(function () {
        Route::get('{token}', [NewPasswordController::class, 'create'])->name('canvas.reset-password.view');
        Route::post('/', [NewPasswordController::class, 'store'])->name('canvas.reset-password');
    });

    // Logout route...
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('canvas.logout');
});
