<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])
        ->name('auth.google.redirect');

    Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleAuthController::class, 'callback'])
        ->name('auth.google.callback');

    // Redirection de /register vers /inscription
    Route::get('register', function () {
        return redirect()->route('connexion');
    });

    // Volt::route('inscription', 'pages.auth.register')
    //     ->name('register');

    // Redirection de /login vers /connexion
    Route::get('login', function () {
        return redirect()->route('connexion');
    })->name('login');

    // Volt::route('connexion', 'pages.auth.login')
    //     ->name('login');

    Route::get('forgot-password', \App\Livewire\Auth\ForgotPassword::class)
        ->name('password.request');

    Route::get('reset-password/{token}', \App\Livewire\Auth\ResetPassword::class)
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
