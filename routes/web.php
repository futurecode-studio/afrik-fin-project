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
Route::get('/services/formations', \App\Livewire\Pages\ServicesFormation::class)->name('services-formation');
Route::get('/services/bourse', \App\Livewire\Pages\ServicesBourse::class)->name('services-bourse');
Route::get('/services/conseil', \App\Livewire\Pages\ServicesConseil::class)->name('services-conseil');
Route::get('/services/{slug}', \App\Livewire\Pages\ServiceDetail::class)->name('service-detail');

Route::get('/actualites', \App\Livewire\Pages\Actualites::class)->name('actualites');
Route::get('/actualites/{slug}', \App\Livewire\Pages\ActualiteDetail::class)->name('actualite-detail');

Route::get('/formations', \App\Livewire\Pages\Formations::class)->name('formations');
Route::get('/formations/{slug}', \App\Livewire\Pages\FormationDetail::class)->name('formation-detail');

// Payment callbacks
Route::post('/payment/kkiapay/callback', [\App\Http\Controllers\PaymentController::class, 'kkiapayCallback'])->name('payment.kkiapay.callback');
Route::post('/payment/fedapay/callback', [\App\Http\Controllers\PaymentController::class, 'fedapayCallback'])->name('payment.fedapay.callback');
Route::get('/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/bourse', \App\Livewire\Pages\Bourse::class)->name('bourse');
Route::get('/vl-fcp', \App\Livewire\Pages\VlFcp::class)->name('vl-fcp');
Route::get('/about', \App\Livewire\Pages\About::class)->name('about');
Route::get('/newsletter', \App\Livewire\Pages\Newsletter::class)->name('newsletter');
Route::get('/contact', \App\Livewire\Pages\Contact::class)->name('contact');

Route::get('/connexion', \App\Livewire\Auth\Login::class)->name('connexion');
Route::get('/inscription', \App\Livewire\Auth\Register::class)->name('inscription')->middleware('guest');

// Certificats
Route::middleware('auth')->group(function () {
    Route::get('/certificate/{enrollment}/download', [\App\Http\Controllers\CertificateController::class, 'download'])->name('certificate.download');
    Route::get('/certificate/{enrollment}/view', [\App\Http\Controllers\CertificateController::class, 'view'])->name('certificate.view');
});
Route::post('/certificate/verify', [\App\Http\Controllers\CertificateController::class, 'verify'])->name('certificate.verify');

// Client Routes
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Client\Dashboard::class)->name('dashboard');
    Route::get('/formations', \App\Livewire\Client\Formations::class)->name('formations');
    Route::get('/formations/{slug}', \App\Livewire\Client\Formation::class)->name('formation');
    Route::get('/certificates', \App\Livewire\Client\Certificates::class)->name('certificates');
    Route::get('/profile', \App\Livewire\Client\Profile::class)->name('profile');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Pages\Dashboard::class)->name('dashboard');
    Route::get('/profile', \App\Livewire\Admin\Profile::class)->name('profile');
    Route::get('/articles', \App\Livewire\Admin\Articles::class)->name('articles');
    Route::get('/formations', \App\Livewire\Admin\Formations::class)->name('formations');
    Route::get('/formations/{formation}/modules', \App\Livewire\Admin\FormationModules::class)->name('formations.modules');
    Route::get('/formations/{formation}/modules/{module}/lessons', \App\Livewire\Admin\ModuleLessons::class)->name('formations.modules.lessons');
    Route::get('/formations/{formation}/modules/{module}/quiz', \App\Livewire\Admin\ModuleQuizManager::class)->name('formations.modules.quiz');
    Route::get('/users', \App\Livewire\Admin\Users::class)->name('users');
    Route::get('/stock-data', \App\Livewire\Admin\StockData::class)->name('stock-data');
    Route::get('/transactions', \App\Livewire\Admin\Transactions::class)->name('transactions');
    Route::get('/appointments', \App\Livewire\Admin\Appointments::class)->name('appointments');
    Route::get('/newsletters', \App\Livewire\Admin\Newsletters::class)->name('newsletters');
    Route::get('/contacts', \App\Livewire\Admin\Contacts::class)->name('contacts');
    Route::get('/statistics', \App\Livewire\Admin\Statistics::class)->name('statistics');
    Route::get('/api-config', \App\Livewire\Admin\ApiConfig::class)->name('api-config');
});

require __DIR__.'/auth.php';
