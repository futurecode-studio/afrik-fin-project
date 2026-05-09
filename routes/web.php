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

// Routes d'investissement
Route::get('/investir/actions-brvm', \App\Livewire\Pages\InvestirActionsBrvm::class)->name('investir.actions-brvm');
Route::get('/investir/obligations', \App\Livewire\Pages\InvestirObligations::class)->name('investir.obligations');
Route::get('/investir/fcp', \App\Livewire\Pages\InvestirFcp::class)->name('investir.fcp');
Route::get('/investir/vl-fcp', \App\Livewire\Pages\VlFcp::class)->name('investir.vl-fcp');

Route::get('/about', \App\Livewire\Pages\About::class)->name('about');
Route::get('/carriere', \App\Livewire\Pages\Carriere::class)->name('carriere');
Route::get('/newsletter', \App\Livewire\Pages\Newsletter::class)->name('newsletter');
Route::get('/contact', \App\Livewire\Pages\Contact::class)->name('contact');
Route::get('/partenaires', \App\Livewire\Pages\Partners::class)->name('partenaires');
Route::get('/equipe', \App\Livewire\Pages\Team::class)->name('team');

// Événements publiques
Route::get('/evenements', \App\Livewire\Pages\EventsList::class)->name('events-list');
Route::get('/evenements/{slug}', \App\Livewire\Pages\EventDetail::class)->name('event-detail');
Route::get('/evenements/ticket/{qrCode}', \App\Livewire\Pages\EventTicketPublic::class)->name('event.ticket.public');

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
    Route::get('/evenements', \App\Livewire\Client\MyEvents::class)->name('my-events');
    Route::get('/evenements/{id}/ticket', \App\Livewire\Client\MyEventTicket::class)->name('event.ticket');
});

// Admin Routes
Route::middleware(['auth', 'permission:dashboard.view'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Pages\Dashboard::class)->name('dashboard');
    Route::get('/profile', \App\Livewire\Admin\Profile::class)->name('profile')->middleware('permission:users.view');
    Route::get('/articles', \App\Livewire\Admin\Articles::class)->name('articles')->middleware('permission:articles.view');
    Route::get('/formations', \App\Livewire\Admin\Formations::class)->name('formations')->middleware('permission:formations.view');
    Route::get('/formations/{formation}/modules', \App\Livewire\Admin\FormationModules::class)->name('formations.modules')->middleware('permission:formations.view');
    Route::get('/formations/{formation}/modules/{module}/lessons', \App\Livewire\Admin\ModuleLessons::class)->name('formations.modules.lessons')->middleware('permission:formations.view');
    Route::get('/formations/{formation}/modules/{module}/quiz', \App\Livewire\Admin\ModuleQuizManager::class)->name('formations.modules.quiz')->middleware('permission:formations.view');
    Route::get('/users', \App\Livewire\Admin\Users::class)->name('users')->middleware('permission:users.view');
    Route::get('/stock-data', \App\Livewire\Admin\StockData::class)->name('stock-data')->middleware('permission:stock-data.view');
    Route::get('/government-bonds', \App\Livewire\Admin\GovernmentBonds::class)->name('government-bonds')->middleware('permission:government-bonds.view');
    Route::get('/job-applications', \App\Livewire\Admin\JobApplications::class)->name('job-applications')->middleware('permission:users.view');
    Route::get('/transactions', \App\Livewire\Admin\Transactions::class)->name('transactions')->middleware('permission:transactions.view');
    Route::get('/appointments', \App\Livewire\Admin\Appointments::class)->name('appointments')->middleware('permission:appointments.view');
    Route::get('/newsletters', \App\Livewire\Admin\Newsletters::class)->name('newsletters')->middleware('permission:newsletters.view');
    Route::get('/contacts', \App\Livewire\Admin\Contacts::class)->name('contacts')->middleware('permission:contacts.view');
    Route::get('/statistics', \App\Livewire\Admin\Statistics::class)->name('statistics')->middleware('permission:statistics.view');
    Route::get('/partners', \App\Livewire\Admin\Partners::class)->name('partners')->middleware('permission:partners.view');
    Route::get('/social-links', \App\Livewire\Admin\SocialLinks::class)->name('social-links');
    Route::get('/team', \App\Livewire\Admin\TeamMembers::class)->name('team')->middleware('permission:team.view');
    Route::get('/roles', \App\Livewire\Admin\Roles::class)->name('roles')->middleware('permission:roles.view');
    Route::get('/api-config', \App\Livewire\Admin\ApiConfig::class)->name('api-config')->middleware('permission:users.view');

    // Events Admin
    Route::get('/events', \App\Livewire\Admin\Events::class)->name('events')->middleware('permission:events.view');
    Route::get('/events/{event}/registrations', \App\Livewire\Admin\EventRegistrations::class)->name('event.registrations')->middleware('permission:event_registrations.view');
    Route::get('/events/{event}/checkin', \App\Livewire\Admin\EventCheckInManager::class)->name('event.checkin')->middleware('permission:event_checkin.manage');
});

require __DIR__.'/auth.php';
