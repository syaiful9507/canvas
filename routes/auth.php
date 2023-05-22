<?php

declare(strict_types=1);

use Canvas\Http\Controllers\Auth\AuthenticatedSessionController;
use Canvas\Http\Controllers\Auth\NewPasswordController;
use Canvas\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->as('canvas.')->group(function () {
    Route::prefix('login')->group(function () {
        Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login.view');
        Route::post('/', [AuthenticatedSessionController::class, 'store'])->name('login');
    });

    Route::prefix('forgot-password')->group(function () {
        Route::get('/', [PasswordResetLinkController::class, 'create'])->name('forgot-password.view');
        Route::post('/', [PasswordResetLinkController::class, 'store'])->name('forgot-password');
    });

    Route::prefix('reset-password')->group(function () {
        Route::get('{token}', [NewPasswordController::class, 'create'])->name('reset-password.view');
        Route::post('/', [NewPasswordController::class, 'store'])->name('reset-password');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
