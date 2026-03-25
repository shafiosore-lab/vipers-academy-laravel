<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Governance\AgeVerificationController;
use App\Http\Controllers\Governance\DisciplinaryCaseController;
use App\Http\Controllers\Governance\AppealController;
use App\Http\Controllers\Governance\ProtestController;

/*
|--------------------------------------------------------------------------
| Governance & Compliance Routes
|--------------------------------------------------------------------------
|
| Routes for managing age verification, disciplinary cases, appeals, and protests
| These routes are accessible to admin and super-admin roles
|
*/

// Admin Governance Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Age Verification Management
    Route::prefix('age-verification')->name('age-verification.')->group(function () {
        Route::get('/', [AgeVerificationController::class, 'index'])->name('index');
        Route::get('/rules', [AgeVerificationController::class, 'rules'])->name('rules.index');
        Route::get('/rules/create', [AgeVerificationController::class, 'createRule'])->name('rules.create');
        Route::post('/rules', [AgeVerificationController::class, 'storeRule'])->name('rules.store');
        Route::get('/rules/{rule}/edit', [AgeVerificationController::class, 'editRule'])->name('rules.edit');
        Route::put('/rules/{rule}', [AgeVerificationController::class, 'updateRule'])->name('rules.update');
        Route::delete('/rules/{rule}', [AgeVerificationController::class, 'destroyRule'])->name('rules.destroy');
        Route::post('/rules/{rule}/toggle', [AgeVerificationController::class, 'toggleRule'])->name('rules.toggle');

        // Player verification
        Route::get('/players', [AgeVerificationController::class, 'players'])->name('players.index');
        Route::get('/players/{player}/verify', [AgeVerificationController::class, 'verifyPlayer'])->name('players.verify');
        Route::post('/players/{player}/verify', [AgeVerificationController::class, 'storeVerification'])->name('players.store-verification');
        Route::get('/players/{player}/history', [AgeVerificationController::class, 'verificationHistory'])->name('players.history');
        Route::post('/players/{player}/flag', [AgeVerificationController::class, 'flagPlayer'])->name('players.flag');
        Route::post('/players/{player}/unflag', [AgeVerificationController::class, 'unflagPlayer'])->name('players.unflag');

        // Reports
        Route::get('/reports/age-compliance', [AgeVerificationController::class, 'ageComplianceReport'])->name('reports.compliance');
        Route::get('/reports/verification-status', [AgeVerificationController::class, 'verificationStatusReport'])->name('reports.status');
        Route::get('/reports/age-alerts', [AgeVerificationController::class, 'ageAlertsReport'])->name('reports.alerts');
    });

    // Disciplinary Cases Management
    Route::prefix('disciplinary')->name('disciplinary.')->group(function () {
        Route::get('/', [DisciplinaryCaseController::class, 'index'])->name('index');
        Route::get('/cases', [DisciplinaryCaseController::class, 'cases'])->name('cases.index');
        Route::get('/cases/create', [DisciplinaryCaseController::class, 'createCase'])->name('cases.create');
        Route::post('/cases', [DisciplinaryCaseController::class, 'storeCase'])->name('cases.store');
        Route::get('/cases/{case}', [DisciplinaryCaseController::class, 'showCase'])->name('cases.show');
        Route::get('/cases/{case}/edit', [DisciplinaryCaseController::class, 'editCase'])->name('cases.edit');
        Route::put('/cases/{case}', [DisciplinaryCaseController::class, 'updateCase'])->name('cases.update');
        Route::delete('/cases/{case}', [DisciplinaryCaseController::class, 'destroyCase'])->name('cases.destroy');

        // Case actions
        Route::post('/cases/{case}/start-review', [DisciplinaryCaseController::class, 'startReview'])->name('cases.start-review');
        Route::post('/cases/{case}/make-decision', [DisciplinaryCaseController::class, 'makeDecision'])->name('cases.make-decision');
        Route::post('/cases/{case}/appeal', [DisciplinaryCaseController::class, 'createAppeal'])->name('cases.create-appeal');

        // Case management
        Route::get('/cases/{case}/history', [DisciplinaryCaseController::class, 'caseHistory'])->name('cases.history');
        Route::get('/cases/{case}/documents', [DisciplinaryCaseController::class, 'caseDocuments'])->name('cases.documents');
        Route::post('/cases/{case}/add-evidence', [DisciplinaryCaseController::class, 'addEvidence'])->name('cases.add-evidence');
        Route::post('/cases/{case}/add-witness', [DisciplinaryCaseController::class, 'addWitness'])->name('cases.add-witness');

        // Reports
        Route::get('/reports/case-summary', [DisciplinaryCaseController::class, 'caseSummaryReport'])->name('reports.summary');
        Route::get('/reports/suspensions', [DisciplinaryCaseController::class, 'suspensionsReport'])->name('reports.suspensions');
        Route::get('/reports/incident-types', [DisciplinaryCaseController::class, 'incidentTypesReport'])->name('reports.incident-types');
        Route::get('/reports/trends', [DisciplinaryCaseController::class, 'trendsReport'])->name('reports.trends');
    });

    // Appeals Management
    Route::prefix('appeals')->name('appeals.')->group(function () {
        Route::get('/', [AppealController::class, 'index'])->name('index');
        Route::get('/pending', [AppealController::class, 'pending'])->name('pending');
        Route::get('/resolved', [AppealController::class, 'resolved'])->name('resolved');
        Route::get('/create', [AppealController::class, 'create'])->name('create');
        Route::post('/', [AppealController::class, 'store'])->name('store');
        Route::get('/{appeal}', [AppealController::class, 'show'])->name('show');
        Route::get('/{appeal}/edit', [AppealController::class, 'edit'])->name('edit');
        Route::put('/{appeal}', [AppealController::class, 'update'])->name('update');
        Route::delete('/{appeal}', [AppealController::class, 'destroy'])->name('destroy');

        // Appeal actions
        Route::post('/{appeal}/start-review', [AppealController::class, 'startReview'])->name('start-review');
        Route::post('/{appeal}/accept', [AppealController::class, 'accept'])->name('accept');
        Route::post('/{appeal}/dismiss', [AppealController::class, 'dismiss'])->name('dismiss');
        Route::post('/{appeal}/modify', [AppealController::class, 'modify'])->name('modify');
        Route::post('/{appeal}/withdraw', [AppealController::class, 'withdraw'])->name('withdraw');

        // Reports
        Route::get('/reports/appeal-summary', [AppealController::class, 'appealSummaryReport'])->name('reports.summary');
        Route::get('/reports/appeal-outcomes', [AppealController::class, 'appealOutcomesReport'])->name('reports.outcomes');
        Route::get('/reports/appeal-trends', [AppealController::class, 'appealTrendsReport'])->name('reports.trends');
    });

    // Protests Management
    Route::prefix('protests')->name('protests.')->group(function () {
        Route::get('/', [ProtestController::class, 'index'])->name('index');
        Route::get('/pending', [ProtestController::class, 'pending'])->name('pending');
        Route::get('/resolved', [ProtestController::class, 'resolved'])->name('resolved');
        Route::get('/create', [ProtestController::class, 'create'])->name('create');
        Route::post('/', [ProtestController::class, 'store'])->name('store');
        Route::get('/{protest}', [ProtestController::class, 'show'])->name('show');
        Route::get('/{protest}/edit', [ProtestController::class, 'edit'])->name('edit');
        Route::put('/{protest}', [ProtestController::class, 'update'])->name('update');
        Route::delete('/{protest}', [ProtestController::class, 'destroy'])->name('destroy');

        // Protest actions
        Route::post('/{protest}/start-review', [ProtestController::class, 'startReview'])->name('start-review');
        Route::post('/{protest}/uphold', [ProtestController::class, 'uphold'])->name('uphold');
        Route::post('/{protest}/reject', [ProtestController::class, 'reject'])->name('reject');
        Route::post('/{protest}/withdraw', [ProtestController::class, 'withdraw'])->name('withdraw');

        // Reports
        Route::get('/reports/protest-summary', [ProtestController::class, 'protestSummaryReport'])->name('reports.summary');
        Route::get('/reports/protest-types', [ProtestController::class, 'protestTypesReport'])->name('reports.types');
        Route::get('/reports/protest-outcomes', [ProtestController::class, 'protestOutcomesReport'])->name('reports.outcomes');
    });
});

// Super Admin Governance Routes (Multi-Organization)
Route::middleware(['auth', 'super.admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    // Age Verification Management (Multi-Org)
    Route::prefix('age-verification')->name('age-verification.')->group(function () {
        Route::get('/', [AgeVerificationController::class, 'superAdminIndex'])->name('index');
        Route::get('/organizations/{organization}/rules', [AgeVerificationController::class, 'organizationRules'])->name('organizations.rules');
        Route::get('/organizations/{organization}/players', [AgeVerificationController::class, 'organizationPlayers'])->name('organizations.players');
        Route::get('/reports/multi-org-compliance', [AgeVerificationController::class, 'multiOrgComplianceReport'])->name('reports.multi-org-compliance');
        Route::get('/reports/organization-summary', [AgeVerificationController::class, 'organizationSummaryReport'])->name('reports.organization-summary');
    });

    // Disciplinary Cases Management (Multi-Org)
    Route::prefix('disciplinary')->name('disciplinary.')->group(function () {
        Route::get('/', [DisciplinaryCaseController::class, 'superAdminIndex'])->name('index');
        Route::get('/organizations/{organization}/cases', [DisciplinaryCaseController::class, 'organizationCases'])->name('organizations.cases');
        Route::get('/organizations/{organization}/suspensions', [DisciplinaryCaseController::class, 'organizationSuspensions'])->name('organizations.suspensions');
        Route::get('/reports/multi-org-summary', [DisciplinaryCaseController::class, 'multiOrgSummaryReport'])->name('reports.multi-org-summary');
        Route::get('/reports/cross-org-trends', [DisciplinaryCaseController::class, 'crossOrgTrendsReport'])->name('reports.cross-org-trends');
    });

    // Appeals Management (Multi-Org)
    Route::prefix('appeals')->name('appeals.')->group(function () {
        Route::get('/', [AppealController::class, 'superAdminIndex'])->name('index');
        Route::get('/organizations/{organization}/appeals', [AppealController::class, 'organizationAppeals'])->name('organizations.appeals');
        Route::get('/reports/multi-org-summary', [AppealController::class, 'multiOrgSummaryReport'])->name('reports.multi-org-summary');
    });

    // Protests Management (Multi-Org)
    Route::prefix('protests')->name('protests.')->group(function () {
        Route::get('/', [ProtestController::class, 'superAdminIndex'])->name('index');
        Route::get('/organizations/{organization}/protests', [ProtestController::class, 'organizationProtests'])->name('organizations.protests');
        Route::get('/reports/multi-org-summary', [ProtestController::class, 'multiOrgSummaryReport'])->name('reports.multi-org-summary');
    });
});

// API Routes for Governance
Route::prefix('api/governance')->name('api.governance.')->middleware('auth')->group(function () {
    // Age Verification API
    Route::prefix('age-verification')->group(function () {
        Route::get('/players/{player}/status', [AgeVerificationController::class, 'getPlayerStatus'])->name('age-verification.player-status');
        Route::get('/rules/{organization}/active', [AgeVerificationController::class, 'getActiveRules'])->name('age-verification.active-rules');
        Route::post('/players/{player}/verify', [AgeVerificationController::class, 'apiVerifyPlayer'])->name('age-verification.verify-player');
    });

    // Disciplinary Cases API
    Route::prefix('disciplinary')->group(function () {
        Route::get('/cases/{case}/status', [DisciplinaryCaseController::class, 'getCaseStatus'])->name('disciplinary.case-status');
        Route::get('/players/{player}/history', [DisciplinaryCaseController::class, 'getPlayerHistory'])->name('disciplinary.player-history');
        Route::get('/suspensions/active', [DisciplinaryCaseController::class, 'getActiveSuspensions'])->name('disciplinary.active-suspensions');
    });

    // Appeals API
    Route::prefix('appeals')->group(function () {
        Route::get('/cases/{case}/appeals', [AppealController::class, 'getCaseAppeals'])->name('appeals.case-appeals');
        Route::get('/players/{player}/appeals', [AppealController::class, 'getPlayerAppeals'])->name('appeals.player-appeals');
    });

    // Protests API
    Route::prefix('protests')->group(function () {
        Route::get('/matches/{match}/protests', [ProtestController::class, 'getMatchProtests'])->name('protests.match-protests');
        Route::get('/teams/{team}/protests', [ProtestController::class, 'getTeamProtests'])->name('protests.team-protests');
    });
});
