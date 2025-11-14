<?php

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


Route::get('/', \App\Livewire\Pages\Home::class)->name('home');
Route::get('/services', \App\Livewire\Pages\Services::class)->name('services');
Route::get('/services/{slug}', \App\Livewire\Pages\ServiceDetail::class)->name('service-detail');
Route::get('/actualites', \App\Livewire\Pages\Actualites::class)->name('actualites');
Route::get('/actualites/{slug}', \App\Livewire\Pages\ActualiteDetail::class)->name('actualite-detail');
Route::get('/formations', \App\Livewire\Pages\Formations::class)->name('formations');
Route::get('/formations/{slug}', \App\Livewire\Pages\FormationDetail::class)->name('formation-detail');
Route::get('/bourse', \App\Livewire\Pages\Bourse::class)->name('bourse');
Route::get('/about', \App\Livewire\Pages\About::class)->name('about');
Route::get('/newsletter', \App\Livewire\Pages\Newsletter::class)->name('newsletter');
Route::get('/contact', \App\Livewire\Pages\Contact::class)->name('contact');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
