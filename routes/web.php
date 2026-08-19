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
Route::get('/services/gestion-sous-mandat', \App\Livewire\Pages\Services\GestionSousMandat::class)->name('services.mandat');
Route::get('/services/institutionnel', \App\Livewire\Pages\Services\PortailInstitutionnel::class)->name('services.institutionnel');
Route::get('/services/{slug}', \App\Livewire\Pages\ServiceDetail::class)->name('service-detail');

Route::get('/actualites', \App\Livewire\Pages\Actualites::class)->name('actualites');
Route::get('/actualites/{slug}', \App\Livewire\Pages\ActualiteDetail::class)->name('actualite-detail');

Route::get('/formations', \App\Livewire\Pages\Formations::class)->name('formations');
Route::get('/formations/{slug}', \App\Livewire\Pages\FormationDetail::class)->name('formation-detail');

// Payment callbacks
Route::post('/payment/kkiapay/callback', [\App\Http\Controllers\PaymentController::class, 'kkiapayCallback'])->name('payment.kkiapay.callback');
Route::post('/payment/fedapay/callback', [\App\Http\Controllers\PaymentController::class, 'fedapayCallback'])->name('payment.fedapay.callback');
Route::match(['get', 'post'], '/payment/feexpay/callback', [\App\Http\Controllers\PaymentController::class, 'feexpayCallback'])->name('payment.feexpay.callback');
Route::get('/payment/success', \App\Livewire\Pages\PaymentConfirmation::class)->name('payment.success');
Route::get('/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

Route::get('/guide-bourse', \App\Livewire\Pages\GuideBourse::class)->name('guide-bourse');

// Investir (hub / parcours profil CI) — pages retirées du site public
$retiredInvestir = [
    'investir.actions-brvm' => '/investir/actions-brvm',
    'investir.obligations' => '/investir/obligations',
    'investir.fcp' => '/investir/fcp',
    'investir.vl-fcp' => '/investir/vl-fcp',
    'investir.hub' => '/investir',
    'investir.comment' => '/investir/comment',
    'investir.profil-test' => '/investir/test-profil',
    'investir.profil' => '/investir/votre-profil',
    'investir.partenaires' => '/investir/partenaires-agrees',
    'investir.opcvm' => '/investir/opcvm',
    'investir.vl' => '/investir/valeurs-liquidatives',
];
foreach ($retiredInvestir as $name => $uri) {
    Route::redirect($uri, '/', 301)->name($name);
}

Route::redirect('/ouverture-compte-sgi', '/demande-mise-en-relation', 301)->name('ouverture-compte-sgi');
Route::permanentRedirect('/investir/fcp/{id}', '/')->name('investir.fcp-detail');
Route::permanentRedirect('/investir/{any}', '/')->where('any', '.*');

Route::get('/marches', \App\Livewire\Pages\Marches\MarchesFinanciers::class)->name('marches.index');
Route::get('/marches/cotations', \App\Livewire\Pages\Marches\CotationsActions::class)->name('marches.cotations');
Route::get('/marches/palmares', \App\Livewire\Pages\Marches\PalmaresActions::class)->name('marches.palmares');
Route::get('/marches/comparateur', \App\Livewire\Pages\Marches\ComparateurActions::class)->name('marches.comparateur');
Route::get('/marches/indices', \App\Livewire\Pages\Marches\IndicesBrvm::class)->name('marches.indices');
Route::get('/marches/obligations', \App\Livewire\Pages\Marches\MarcheObligataire::class)->name('marches.obligations');
Route::get('/marches/screener', \App\Livewire\Pages\Marches\ScreenerInvestissement::class)->name('marches.screener');
Route::get('/marches/secteurs', \App\Livewire\Pages\Marches\RapportSectoriel::class)->name('marches.secteurs');
Route::get('/marches/introductions', \App\Livewire\Pages\Marches\SuiviIntroductions::class)->name('marches.introductions');
Route::get('/marches/carnet-ordres', \App\Livewire\Pages\Marches\CarnetOrdres::class)->name('marches.carnet');
Route::get('/marches/bibliotheque', \App\Livewire\Pages\Marches\BibliothequeRecherche::class)->name('marches.bibliotheque');
Route::get('/marches/comparateur-multi', \App\Livewire\Pages\Marches\ComparateurMultiActifs::class)->name('marches.comparateur-multi');
Route::get('/marches/produits-structures', \App\Livewire\Pages\Marches\ProduitsStructures::class)->name('marches.produits-structures');
Route::get('/marches/analyse-pro', \App\Livewire\Pages\Marches\AnalyseGraphiquePro::class)->name('marches.analyse-pro');
Route::get('/marches/actions/{symbol}', \App\Livewire\Pages\Marches\FicheAction::class)->name('marches.action');
Route::get('/marches/obligations/{id}', \App\Livewire\Pages\Marches\DetailObligation::class)->name('marches.obligation');
Route::get('/marches/analyse-pro/{symbol}', \App\Livewire\Pages\Marches\AnalyseGraphiquePro::class)->name('marches.analyse-pro.symbol');

Route::redirect('/marches/calendrier', '/', 301)->name('marches.calendrier');
Route::redirect('/marches/recherche', '/', 301)->name('marches.recherche');
Route::redirect('/marches/carte', '/', 301);
Route::permanentRedirect('/marches/certificats/{slug}', '/')->name('marches.certificat');

Route::get('/carriere', \App\Livewire\Pages\Carriere::class)->name('carriere');
Route::get('/newsletter', \App\Livewire\Pages\Newsletter::class)->name('newsletter');
Route::get('/contact', \App\Livewire\Pages\Contact::class)->name('contact');
Route::get('/faq', \App\Livewire\Pages\Faq::class)->name('faq');
Route::get('/glossaire', \App\Livewire\Pages\Glossaire::class)->name('glossaire');
Route::redirect('/recherche', '/', 301)->name('search');
Route::redirect('/aide', '/contact', 301)->name('aide');
Route::get('/agrements', \App\Livewire\Pages\Agrements::class)->name('agrements');
Route::get('/legal/{slug}', \App\Livewire\Pages\LegalPage::class)->name('legal.show');
Route::get('/certificat/verifier/{number?}', \App\Livewire\Pages\CertificateVerify::class)->name('certificate.verify.show');
Route::redirect('/support/ticket', '/contact', 301)->name('support.ticket');
Route::get('/outils/interets-composes', \App\Livewire\Pages\Outils\SimulateurInteretsComposes::class)->name('outils.interets-composes');
Route::get('/outils/rendement-obligataire', \App\Livewire\Pages\Outils\SimulateurObligataire::class)->name('outils.rendement-obligataire');
Route::get('/outils/frais-fiscalite', \App\Livewire\Pages\Outils\EstimateurFraisFiscalite::class)->name('outils.frais');
Route::get('/outils/performance-fcp', \App\Livewire\Pages\Outils\AnalysePerformanceFcp::class)->name('outils.performance-fcp');
Route::get('/demande-mise-en-relation', \App\Livewire\Pages\MiseEnRelation::class)->name('mise-en-relation');
Route::get('/mise-en-relation', \App\Livewire\Pages\MiseEnRelation::class);
Route::get('/partenaires', \App\Livewire\Pages\Partners::class)->name('partenaires');
Route::get('/partenaires/{id}', \App\Livewire\Pages\PartenaireDetail::class)->name('partenaires.show');
Route::redirect('/equipe', '/a-propos', 301);
Route::get('/a-propos', \App\Livewire\Pages\Team::class)->name('team');
Route::get('/panier', \App\Livewire\Pages\Panier::class)->name('panier');

// Événements publics (routes spécifiques avant {slug})
Route::get('/evenements', \App\Livewire\Pages\EventsList::class)->name('events-list');
Route::get('/evenements/ticket/{qrCode}', \App\Livewire\Pages\EventTicketPublic::class)->name('event.ticket.public');
Route::get('/evenements/commande/{orderNumber}', \App\Livewire\Pages\EventOrderConfirmation::class)->name('event.order.confirmation');
Route::get('/evenements/{slug}', \App\Livewire\Pages\EventDetail::class)->name('event-detail');

Route::get('/connexion', \App\Livewire\Auth\Login::class)->name('connexion')->middleware('guest');
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
    Route::get('/historique-apprentissage', \App\Livewire\Client\LearningHistory::class)->name('learning-history');
    Route::get('/notes', \App\Livewire\Client\MesNotes::class)->name('notes');
    Route::get('/favoris', \App\Livewire\Client\MesFavoris::class)->name('favorites');
    Route::get('/question-formateur', \App\Livewire\Client\QuestionFormateur::class)->name('ask-instructor');
    Route::get('/formations/{slug}/forum', \App\Livewire\Client\ForumDiscussion::class)->name('formation.forum');
    Route::get('/formations/{slug}/ressources', \App\Livewire\Client\RessourcesFormation::class)->name('formation.resources');
    Route::get('/formations/{slug}/evaluation', \App\Livewire\Client\EvaluationFormation::class)->name('formation.review');
    Route::get('/formations/{slug}/progression', \App\Livewire\Client\FormationProgress::class)->name('formation.progress');
    Route::get('/formations/{slug}/terminee', \App\Livewire\Client\FormationCompleted::class)->name('formation.completed');
    Route::get('/formations/{slug}/quiz/{quiz}/intro', \App\Livewire\Client\QuizIntro::class)->name('quiz.intro');
    Route::get('/formations/{slug}/quiz/{quiz}', \App\Livewire\Client\QuizTake::class)->name('quiz.take');
    Route::get('/formations/{slug}/quiz/{quiz}/resultat/{attempt}', \App\Livewire\Client\QuizResult::class)->name('quiz.result');
    Route::get('/formations/{slug}/examen/{quiz}/resultat/{attempt}', \App\Livewire\Client\ExamResult::class)->name('exam.result');
    Route::get('/formations/{slug}/examen/{quiz}/felicitations/{attempt}', \App\Livewire\Client\ExamCongratulations::class)->name('exam.congrats');
    Route::get('/formations/{slug}', \App\Livewire\Client\Formation::class)->name('formation');
    Route::get('/certificates', \App\Livewire\Client\Certificates::class)->name('certificates');
    Route::get('/certificates/{enrollment}', \App\Livewire\Client\CertificateShow::class)->name('certificate.show');
    Route::get('/profile', \App\Livewire\Client\Profile::class)->name('profile');
    Route::get('/interets', \App\Livewire\Client\Interests::class)->name('interests');
    Route::get('/liste-suivi', \App\Livewire\Client\Watchlist::class)->name('watchlist');
    Route::get('/patrimoine', \App\Livewire\Client\Patrimoine::class)->name('patrimoine');
    Route::get('/alertes', \App\Livewire\Client\AlertesMarche::class)->name('alertes');
    Route::get('/ordres-programmes', \App\Livewire\Client\OrdresProgrammes::class)->name('ordres');
    Route::get('/rapport-mensuel', \App\Livewire\Client\RapportMensuel::class)->name('rapport-mensuel');
    Route::get('/actualites-portefeuille', \App\Livewire\Client\ActualitesPortefeuille::class)->name('actualites-portefeuille');
    Route::get('/evenements', \App\Livewire\Client\MyEvents::class)->name('my-events');
    Route::get('/evenements/{id}/ticket', \App\Livewire\Client\MyEventTicket::class)->name('event.ticket');
});

// Admin Routes
Route::middleware(['auth', 'admin.panel'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Pages\Dashboard::class)->name('dashboard')->middleware('permission:dashboard.view');
    Route::get('/profile', \App\Livewire\Admin\Profile::class)->name('profile')->middleware('permission:users.view');
    Route::get('/articles', \App\Livewire\Admin\Articles::class)->name('articles')->middleware('permission:articles.view');
    Route::get('/formations', \App\Livewire\Admin\Formations::class)->name('formations')->middleware('permission:formations.view');
    Route::get('/academy/engagement', \App\Livewire\Admin\AnalyseEngagement::class)->name('academy.engagement')->middleware('permission:formations.view');
    Route::get('/academy/formations', \App\Livewire\Admin\AnalysesParFormation::class)->name('academy.formations')->middleware('permission:formations.view');
    Route::get('/academy/exercices', \App\Livewire\Admin\ExerciseCorrections::class)->name('academy.exercises')->middleware('permission:formations.view');
    Route::get('/academy/abandon', \App\Livewire\Admin\AnalyseAbandon::class)->name('academy.abandon')->middleware('permission:formations.view');
    Route::get('/academy/quiz', \App\Livewire\Admin\QuizAnalytics::class)->name('academy.quiz')->middleware('permission:formations.view');
    Route::get('/academy/videos', \App\Livewire\Admin\VideoAnalytics::class)->name('academy.videos')->middleware('permission:formations.view');
    Route::get('/academy/questions', \App\Livewire\Admin\QuestionsFormateur::class)->name('academy.questions')->middleware('permission:formations.view');
    Route::get('/academy/apprenants', \App\Livewire\Admin\SuiviApprenants::class)->name('learners')->middleware('permission:formations.view');
    Route::get('/academy/apprenants/{user}', \App\Livewire\Admin\DetailProgressionApprenant::class)->name('learners.show')->middleware('permission:formations.view');
    Route::get('/formations/{formation}/modules', \App\Livewire\Admin\FormationModules::class)->name('formations.modules')->middleware('permission:formations.view');
    Route::get('/formations/{formation}/modules/{module}/lessons', \App\Livewire\Admin\ModuleLessons::class)->name('formations.modules.lessons')->middleware('permission:formations.view');
    Route::get('/formations/{formation}/modules/{module}/quiz', \App\Livewire\Admin\ModuleQuizManager::class)->name('formations.modules.quiz')->middleware('permission:formations.view');
    Route::get('/formations/{formation}/apprenants', \App\Livewire\Admin\FormationLearners::class)->name('formations.learners')->middleware('permission:formations.view');
    Route::get('/formations/{formation}/apprenants/{user}', \App\Livewire\Admin\FormationLearnerShow::class)->name('formations.learners.show')->middleware('permission:formations.view');
    Route::get('/formations/{formation}', \App\Livewire\Admin\FormationShow::class)->name('formations.show')->middleware('permission:formations.view');
    Route::get('/users', \App\Livewire\Admin\Users::class)->name('users')->middleware('permission:users.view');
    Route::get('/stock-data', \App\Livewire\Admin\StockData::class)->name('stock-data')->middleware('permission:stock-data.view');
    Route::get('/market-advanced', \App\Livewire\Admin\MarketIpos::class)->name('market-advanced')->middleware('permission:stock-data.view');
    Route::get('/government-bonds', \App\Livewire\Admin\GovernmentBonds::class)->name('government-bonds')->middleware('permission:government-bonds.view');
    Route::get('/job-applications', \App\Livewire\Admin\JobApplications::class)->name('job-applications')->middleware('permission:users.view');
    Route::get('/transactions', \App\Livewire\Admin\Transactions::class)->name('transactions')->middleware('permission:transactions.view');
    Route::get('/appointments', \App\Livewire\Admin\Appointments::class)->name('appointments')->middleware('permission:appointments.view');
    Route::get('/newsletters', \App\Livewire\Admin\Newsletters::class)->name('newsletters')->middleware('permission:newsletters.view');
    Route::get('/contacts', \App\Livewire\Admin\Contacts::class)->name('contacts')->middleware('permission:contacts.view');
    Route::get('/statistics', \App\Livewire\Admin\Statistics::class)->name('statistics')->middleware('permission:statistics.view');
    Route::get('/partners', \App\Livewire\Admin\Partners::class)->name('partners')->middleware('permission:partners.view');
    Route::get('/fcps', \App\Livewire\Admin\Funds::class)->name('funds')->middleware('permission:partners.view');
    Route::get('/sgi-sgo', \App\Livewire\Admin\SgiSgoHub::class)->name('sgi-sgo')->middleware('permission:partners.view');
    Route::get('/order-intents', \App\Livewire\Admin\OrderIntents::class)->name('order-intents')->middleware('permission:partners.view');
    Route::get('/sgi-account-requests', \App\Livewire\Admin\SgiAccountRequests::class)->name('sgi-account-requests')->middleware('permission:partners.view');
    Route::get('/sgi-documents', \App\Livewire\Admin\SgiRequiredDocuments::class)->name('sgi-documents')->middleware('permission:partners.view');
    Route::get('/social-links', \App\Livewire\Admin\SocialLinks::class)->name('social-links');
    Route::get('/team', \App\Livewire\Admin\TeamMembers::class)->name('team')->middleware('permission:team.view');
    Route::get('/site-services', \App\Livewire\Admin\SiteServices::class)->name('site-services')->middleware('permission:team.view');
    Route::get('/settings', \App\Livewire\Admin\SystemSettings::class)->name('settings');
    Route::get('/roles', \App\Livewire\Admin\Roles::class)->name('roles')->middleware('permission:roles.view');
    Route::get('/api-config', \App\Livewire\Admin\ApiConfig::class)->name('api-config')->middleware('permission:users.view');

    // Events Admin
    Route::get('/events', \App\Livewire\Admin\Events::class)->name('events')->middleware('permission:events.view');
    Route::get('/events/{event}/registrations', \App\Livewire\Admin\EventRegistrations::class)->name('event.registrations')->middleware('permission:events.view');
    Route::get('/events/{event}/program', \App\Livewire\Admin\EventProgram::class)->name('event.program')->middleware('permission:events.view');
    Route::get('/events/{event}/speakers', \App\Livewire\Admin\EventSpeakers::class)->name('event.speakers')->middleware('permission:events.view');
    Route::get('/events/{event}/documents', \App\Livewire\Admin\EventDocuments::class)->name('event.documents')->middleware('permission:events.view');
    Route::get('/events/{event}/tickets', \App\Livewire\Admin\EventTicketTypes::class)->name('event.tickets')->middleware('permission:events.view');
    Route::get('/events/{event}/checkin', \App\Livewire\Admin\EventCheckInManager::class)->name('event.checkin')->middleware('permission:events.view');
    Route::get('/events/{event}/products', \App\Livewire\Admin\EventProducts::class)->name('event.products')->middleware('permission:events.view');
});

require __DIR__.'/auth.php';
