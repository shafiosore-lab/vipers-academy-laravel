<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\HelpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Website\PlayerController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\Website\BlogController;
use App\Http\Controllers\Website\GalleryController;
use App\Http\Controllers\Website\StaffController;
use App\Http\Controllers\Website\StandingsController;
use App\Http\Controllers\Website\MatchCenterController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\AchievementsController;
use App\Http\Controllers\Website\AnnouncementsController;
use App\Http\Controllers\Website\CareerController;
use App\Http\Controllers\Website\EventsController;
use App\Http\Controllers\Website\TrainingUpdatesController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentUploadController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Achievements
Route::get('/achievements', [AchievementsController::class, 'index'])->name('achievements');

// Events
Route::get('/events', [EventsController::class, 'index'])->name('events');

// Training Updates
Route::get('/training-updates', [TrainingUpdatesController::class, 'index'])->name('training-updates');

// Announcements
Route::get('/announcements', [AnnouncementsController::class, 'index'])->name('announcements');

// Players
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
Route::get('/players/{id}/stats', [PlayerController::class, 'stats'])->name('players.stats')->where('id', '[0-9]+');
Route::get('/players/{id}/overview', [PlayerController::class, 'overview'])->name('players.overview')->where('id', '[0-9]+');
Route::get('/players/{id}/statistics', [PlayerController::class, 'statistics'])->name('players.statistics')->where('id', '[0-9]+');
Route::get('/players/{id}/ai-insights', [PlayerController::class, 'aiInsights'])->name('players.ai-insights')->where('id', '[0-9]+');
Route::get('/players/{id}/biography', [PlayerController::class, 'biography'])->name('players.biography')->where('id', '[0-9]+');
Route::get('/players/{id}/career', [PlayerController::class, 'career'])->name('players.career')->where('id', '[0-9]+');
Route::post('/players/{id}/record-stats', [PlayerController::class, 'recordGameStats'])->name('players.record-stats')->where('id', '[0-9]+');
Route::get('/players/search', [PlayerController::class, 'searchPlayers'])->name('players.search');
Route::get('/players/{id}', [PlayerController::class, 'show'])->name('players.show')->where('id', '[0-9]+');

// Admin route to sync players from gallery
Route::get('/admin/players/sync-gallery', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'syncFromGallery'])
    ->name('admin.players.sync-gallery')
    ->middleware(['auth', 'admin']);

// Temporary route for syncing without auth (remove after use)
Route::get('/sync-players', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'syncFromGallery']);


// Programs
Route::get('/programs', [ProgramController::class, 'index'])->name('programs');
Route::get('/programs/{id}', [ProgramController::class, 'show'])->name('program_detail');
Route::get('/enroll', [App\Http\Controllers\Admin\AdminEnrollmentController::class, 'index'])->name('enrol');
Route::post('/enroll', [App\Http\Controllers\Admin\AdminEnrollmentController::class, 'store'])->name('enrol.store');
Route::get('/enroll/success', [App\Http\Controllers\Admin\AdminEnrollmentController::class, 'success'])->name('enrol.success');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/newsletter/subscribe', [BlogController::class, 'subscribeNewsletter'])->name('blog.newsletter.subscribe');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Staff
Route::get('/staff', [StaffController::class, 'index'])->name('staff');

// Standings
Route::get('/standings', [StandingsController::class, 'index'])->name('standings');
Route::get('/standings/league-table', [StandingsController::class, 'leagueTable'])->name('standings.league-table');
Route::get('/standings/top-scorers', [StandingsController::class, 'topScorers'])->name('standings.top-scorers');
Route::get('/standings/clean-sheets', [StandingsController::class, 'cleanSheets'])->name('standings.clean-sheets');
Route::get('/standings/goalkeepers', [StandingsController::class, 'goalkeepers'])->name('standings.goalkeepers');

// Match Center
Route::get('/match-center', [MatchCenterController::class, 'index'])->name('match-center');
Route::get('/match-center/{id}', [MatchCenterController::class, 'show'])->name('match-center.show');

// Transfer News
Route::get('/transfer-news', [BlogController::class, 'transfers'])->name('transfer-news');

// Player Rankings
Route::get('/player-rankings', [PlayerController::class, 'rankings'])->name('player-rankings');

// Statistics Hub
Route::get('/statistics-hub', [StandingsController::class, 'statisticsHub'])->name('statistics-hub');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Donate/Scholarship Support
Route::get('/donate', function() {
    return view('website.donate.index');
})->name('donate');

// Merchandise
Route::get('/merchandise', function() {
    return view('website.merchandise.index');
})->name('merchandise');

// Careers
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{id}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{id}/apply', [CareerController::class, 'store'])->name('careers.apply');
Route::get('/careers/application/success', [CareerController::class, 'applicationSuccess'])->name('careers.application.success');

// Search
Route::get('/search', function(Request $request) {
    // Basic search implementation - can be expanded
    $query = $request->input('q');
    if (!$query) {
        return redirect()->route('home');
    }
    // For now, redirect to home with search param or implement search controller
    return redirect()->route('home')->with('search', $query);
})->name('search');



// Document Upload System
Route::middleware('auth')->prefix('documents')->name('documents.')->group(function () {
    // Official academy documents (existing)
    Route::get('/', [DocumentController::class, 'index'])->name('index');
    Route::get('/{documentId}', [DocumentController::class, 'show'])->name('show');
    Route::get('/{documentId}/download', [DocumentController::class, 'download'])->name('download');
    Route::post('/{documentId}/sign', [DocumentController::class, 'sign'])->name('sign');
    Route::post('/{documentId}/track-view', [DocumentController::class, 'trackView'])->name('track-view');
    Route::get('/required/status', [DocumentController::class, 'getRequiredDocuments'])->name('required.status');
    Route::get('/expiring/status', [DocumentController::class, 'getExpiringDocuments'])->name('expiring.status');

    // User document uploads (new)
    Route::prefix('upload')->name('upload.')->group(function () {
        Route::get('/', [DocumentUploadController::class, 'index'])->name('index');
        Route::get('/create/{documentType}', [DocumentUploadController::class, 'create'])->name('create');
        Route::post('/store/{documentType}', [DocumentUploadController::class, 'store'])->name('store');
        Route::get('/download/{documentId}', [DocumentUploadController::class, 'download'])->name('download');
        Route::delete('/delete/{documentId}', [DocumentUploadController::class, 'destroy'])->name('destroy');
    });
});

// Help Center
Route::get('/help', function() {
    return view('help.center');
})->name('help.center');



// Student Dashboard Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function() {
        return view('student.dashboard');
    })->name('dashboard');
    Route::get('/learning', function() {
        return view('student.learning');
    })->name('learning');
    Route::get('/profile', function() {
        return view('student.profile');
    })->name('profile');
});

// Dashboard - Redirect based on user role using RoleHierarchyService
Route::get('/dashboard', function() {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Use RoleHierarchyService to determine correct dashboard
    $hierarchyService = new \App\Services\RoleHierarchyService();
    $dashboardRoute = $hierarchyService->getDashboardRouteForUser($user);

    return redirect()->route($dashboardRoute);
})->middleware('auth')->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Addresses
Route::middleware('auth')->prefix('addresses')->name('addresses.')->group(function () {
    Route::get('/', [AddressController::class, 'index'])->name('index');
});

// Additional Auth Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Password Reset Routes (handled by auth.php routes)
// Route::get('/password/reset', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->middleware('guest')->name('password.request');
// Route::post('/password/email', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->middleware('guest')->name('password.email');
// Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
// Route::post('/password/reset', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->middleware('guest')->name('password.store');

// Social Authentication Routes
Route::get('/auth/google', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleFacebookCallback']);

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Players Management
    Route::get('/players', [App\Http\Controllers\Admin\AdminPlayerController::class, 'index'])->name('players.index');
    Route::get('/players/create', [App\Http\Controllers\Admin\AdminPlayerController::class, 'create'])->name('players.create');
    Route::post('/players', [App\Http\Controllers\Admin\AdminPlayerController::class, 'store'])->name('players.store');
    Route::get('/players/{player}', [App\Http\Controllers\Admin\AdminPlayerController::class, 'show'])->name('players.show');
    Route::get('/players/{player}/edit', [App\Http\Controllers\Admin\AdminPlayerController::class, 'edit'])->name('players.edit');
    Route::put('/players/{player}', [App\Http\Controllers\Admin\AdminPlayerController::class, 'update'])->name('players.update');
    Route::put('/players/{player}/approve', [App\Http\Controllers\Admin\AdminPlayerController::class, 'approve'])->name('players.approve');
    Route::post('/players/{player}/approve-temporary', [App\Http\Controllers\Admin\AdminPlayerController::class, 'approveTemporary'])->name('players.approve.temporary');
    Route::put('/players/{player}/reject', [App\Http\Controllers\Admin\AdminPlayerController::class, 'reject'])->name('players.reject');
    Route::delete('/players/{player}', [App\Http\Controllers\Admin\AdminPlayerController::class, 'destroy'])->name('players.destroy');
    Route::post('/players/check-expired', [App\Http\Controllers\Admin\AdminPlayerController::class, 'checkExpiredApprovals'])->name('players.check.expired');

    // Partners Management
    Route::get('/partners', [App\Http\Controllers\Admin\AdminPartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/create', [App\Http\Controllers\Admin\AdminPartnerController::class, 'create'])->name('partners.create');
    Route::post('/partners', [App\Http\Controllers\Admin\AdminPartnerController::class, 'store'])->name('partners.store');
    Route::get('/partners/{partner}', [App\Http\Controllers\Admin\AdminPartnerController::class, 'show'])->name('partners.show');
    Route::get('/partners/{partner}/edit', [App\Http\Controllers\Admin\AdminPartnerController::class, 'edit'])->name('partners.edit');
    Route::put('/partners/{partner}', [App\Http\Controllers\Admin\AdminPartnerController::class, 'update'])->name('partners.update');
    Route::put('/partners/{partner}/approve', [App\Http\Controllers\Admin\AdminPartnerController::class, 'approve'])->name('partners.approve');
    Route::put('/partners/{partner}/reject', [App\Http\Controllers\Admin\AdminPartnerController::class, 'reject'])->name('partners.reject');
    Route::delete('/partners/{partner}', [App\Http\Controllers\Admin\AdminPartnerController::class, 'destroy'])->name('partners.destroy');

    // Programs Management
    Route::get('/programs', [App\Http\Controllers\Admin\AdminProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/create', [App\Http\Controllers\Admin\AdminProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [App\Http\Controllers\Admin\AdminProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{program}', [App\Http\Controllers\Admin\AdminProgramController::class, 'show'])->name('programs.show');
    Route::get('/programs/{program}/edit', [App\Http\Controllers\Admin\AdminProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{program}', [App\Http\Controllers\Admin\AdminProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{program}', [App\Http\Controllers\Admin\AdminProgramController::class, 'destroy'])->name('programs.destroy');

    // Game Statistics
    Route::get('/game-statistics', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'index'])->name('game-statistics.index');
    Route::get('/game-statistics/create', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'create'])->name('game-statistics.create');
    Route::post('/game-statistics', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'store'])->name('game-statistics.store');
    Route::get('/game-statistics/{statistic}', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'show'])->name('game-statistics.show');
    Route::get('/game-statistics/{statistic}/edit', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'edit'])->name('game-statistics.edit');
    Route::put('/game-statistics/{statistic}', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'update'])->name('game-statistics.update');
    Route::delete('/game-statistics/{statistic}', [App\Http\Controllers\Admin\AdminGameStatisticController::class, 'destroy'])->name('game-statistics.destroy');

    // Matches Management
    Route::get('/matches', [App\Http\Controllers\Admin\AdminMatchController::class, 'index'])->name('matches.index');
    Route::get('/matches/create', [App\Http\Controllers\Admin\AdminMatchController::class, 'create'])->name('matches.create');
    Route::post('/matches', [App\Http\Controllers\Admin\AdminMatchController::class, 'store'])->name('matches.store');
    Route::get('/matches/{match}', [App\Http\Controllers\Admin\AdminMatchController::class, 'show'])->name('matches.show');
    Route::get('/matches/{match}/edit', [App\Http\Controllers\Admin\AdminMatchController::class, 'edit'])->name('matches.edit');
    Route::put('/matches/{match}', [App\Http\Controllers\Admin\AdminMatchController::class, 'update'])->name('matches.update');
    Route::delete('/matches/{match}', [App\Http\Controllers\Admin\AdminMatchController::class, 'destroy'])->name('matches.destroy');

    // Standings Management
    Route::get('/standings', [App\Http\Controllers\Admin\AdminStandingsController::class, 'index'])->name('standings.index');
    Route::get('/standings/create', [App\Http\Controllers\Admin\AdminStandingsController::class, 'create'])->name('standings.create');
    Route::post('/standings', [App\Http\Controllers\Admin\AdminStandingsController::class, 'store'])->name('standings.store');
    Route::get('/standings/{standing}', [App\Http\Controllers\Admin\AdminStandingsController::class, 'show'])->name('standings.show');
    Route::get('/standings/{standing}/edit', [App\Http\Controllers\Admin\AdminStandingsController::class, 'edit'])->name('standings.edit');
    Route::put('/standings/{standing}', [App\Http\Controllers\Admin\AdminStandingsController::class, 'update'])->name('standings.update');
    Route::delete('/standings/{standing}', [App\Http\Controllers\Admin\AdminStandingsController::class, 'destroy'])->name('standings.destroy');
    Route::get('/standings/export', [App\Http\Controllers\Admin\AdminStandingsController::class, 'export'])->name('standings.export');
    Route::post('/standings/bulk-import', [App\Http\Controllers\Admin\AdminStandingsController::class, 'bulkImport'])->name('standings.bulk-import');

    // Documents Management
    Route::get('/documents', [App\Http\Controllers\Admin\AdminDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [App\Http\Controllers\Admin\AdminDocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [App\Http\Controllers\Admin\AdminDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [App\Http\Controllers\Admin\AdminDocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/edit', [App\Http\Controllers\Admin\AdminDocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [App\Http\Controllers\Admin\AdminDocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [App\Http\Controllers\Admin\AdminDocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}/preview', [App\Http\Controllers\Admin\AdminDocumentController::class, 'preview'])->name('documents.preview');
    Route::get('/documents/{document}/download', [App\Http\Controllers\Admin\AdminDocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/statistics', [App\Http\Controllers\Admin\AdminDocumentController::class, 'statistics'])->name('documents.statistics');
    Route::post('/documents/bulk', [App\Http\Controllers\Admin\AdminDocumentController::class, 'bulk'])->name('documents.bulk');

    // Blog Management
    Route::get('/blog', [App\Http\Controllers\Admin\AdminBlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [App\Http\Controllers\Admin\AdminBlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [App\Http\Controllers\Admin\AdminBlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}', [App\Http\Controllers\Admin\AdminBlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/{blog}/edit', [App\Http\Controllers\Admin\AdminBlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{blog}', [App\Http\Controllers\Admin\AdminBlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}', [App\Http\Controllers\Admin\AdminBlogController::class, 'destroy'])->name('blog.destroy');

    // Gallery Management
    Route::get('/gallery', [App\Http\Controllers\Admin\AdminGalleryController::class, 'index'])->name('gallery.index');
    Route::get('/gallery/create', [App\Http\Controllers\Admin\AdminGalleryController::class, 'create'])->name('gallery.create');
    Route::post('/gallery', [App\Http\Controllers\Admin\AdminGalleryController::class, 'store'])->name('gallery.store');
    Route::get('/gallery/{gallery}', [App\Http\Controllers\Admin\AdminGalleryController::class, 'show'])->name('gallery.show');
    Route::get('/gallery/{gallery}/edit', [App\Http\Controllers\Admin\AdminGalleryController::class, 'edit'])->name('gallery.edit');
    Route::put('/gallery/{gallery}', [App\Http\Controllers\Admin\AdminGalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{gallery}', [App\Http\Controllers\Admin\AdminGalleryController::class, 'destroy'])->name('gallery.destroy');



    // Payments Management
    Route::get('/payments', [App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'show'])->name('payments.show');
    Route::put('/payments/{payment}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'update'])->name('payments.update');
    Route::get('/payments/financial-report', [App\Http\Controllers\Admin\AdminPaymentController::class, 'financialReport'])->name('payments.financial-report');

    // Payment Categories Management
    Route::get('/payment-categories', [App\Http\Controllers\Admin\PaymentCategoryController::class, 'index'])->name('payment-categories.index');
    Route::get('/payment-categories/create', [App\Http\Controllers\Admin\PaymentCategoryController::class, 'create'])->name('payment-categories.create');
    Route::post('/payment-categories', [App\Http\Controllers\Admin\PaymentCategoryController::class, 'store'])->name('payment-categories.store');
    Route::get('/payment-categories/{paymentCategory}', [App\Http\Controllers\Admin\PaymentCategoryController::class, 'show'])->name('payment-categories.show');
    Route::get('/payment-categories/{paymentCategory}/edit', [App\Http\Controllers\Admin\PaymentCategoryController::class, 'edit'])->name('payment-categories.edit');
    Route::put('/payment-categories/{paymentCategory}', [App\Http\Controllers\Admin\PaymentCategoryController::class, 'update'])->name('payment-categories.update');
    Route::delete('/payment-categories/{paymentCategory}', [App\Http\Controllers\Admin\PaymentCategoryController::class, 'destroy'])->name('payment-categories.destroy');
    Route::get('/payment-categories/{paymentCategory}/toggle-status', [App\Http\Controllers\Admin\PaymentCategoryController::class, 'toggleStatus'])->name('payment-categories.toggle-status');

    // Jobs Management
    Route::get('/jobs', [App\Http\Controllers\Admin\AdminJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [App\Http\Controllers\Admin\AdminJobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [App\Http\Controllers\Admin\AdminJobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}', [App\Http\Controllers\Admin\AdminJobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/edit', [App\Http\Controllers\Admin\AdminJobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [App\Http\Controllers\Admin\AdminJobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{job}', [App\Http\Controllers\Admin\AdminJobController::class, 'destroy'])->name('jobs.destroy');


    // Website Players Management
    Route::get('/website-players', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'index'])->name('website-players.index');
    Route::get('/website-players/create', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'create'])->name('website-players.create');
    Route::post('/website-players', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'store'])->name('website-players.store');
    Route::get('/website-players/{websitePlayer}', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'show'])->name('website-players.show');
    Route::get('/website-players/{websitePlayer}/edit', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'edit'])->name('website-players.edit');
    Route::put('/website-players/{websitePlayer}', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'update'])->name('website-players.update');
    Route::delete('/website-players/{websitePlayer}', [App\Http\Controllers\Admin\AdminWebsitePlayerController::class, 'destroy'])->name('website-players.destroy');

    // Staff Management
    Route::get('/staff', [App\Http\Controllers\Admin\AdminStaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [App\Http\Controllers\Admin\AdminStaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [App\Http\Controllers\Admin\AdminStaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}', [App\Http\Controllers\Admin\AdminStaffController::class, 'show'])->name('staff.show');
    Route::get('/staff/{staff}/edit', [App\Http\Controllers\Admin\AdminStaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [App\Http\Controllers\Admin\AdminStaffController::class, 'update'])->name('staff.update');
    Route::put('/staff/{staff}/activate', [App\Http\Controllers\Admin\AdminStaffController::class, 'activate'])->name('staff.activate');
    Route::put('/staff/{staff}/deactivate', [App\Http\Controllers\Admin\AdminStaffController::class, 'deactivate'])->name('staff.deactivate');
    Route::delete('/staff/{staff}', [App\Http\Controllers\Admin\AdminStaffController::class, 'destroy'])->name('staff.destroy');

    // Attendance Management
    Route::get('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{attendance}', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/{attendance}/check-in', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/{attendance}/check-out', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::get('/attendance/export', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'showExportPage'])->name('attendance.export.page');
    Route::get('/attendance/export/download', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'export'])->name('attendance.export');

    // Training Sessions Management
    Route::resource('training-sessions', App\Http\Controllers\Admin\TrainingSessionController::class);
    Route::post('/training-sessions/{trainingSession}/start', [App\Http\Controllers\Admin\TrainingSessionController::class, 'start'])->name('training-sessions.start');
    Route::post('/training-sessions/{trainingSession}/end', [App\Http\Controllers\Admin\TrainingSessionController::class, 'end'])->name('training-sessions.end');
    Route::post('/training-sessions/{trainingSession}/admit-player', [App\Http\Controllers\Admin\TrainingSessionController::class, 'admitPlayer'])->name('training-sessions.admit-player');
    Route::post('/training-sessions/{trainingSession}/check-out-player', [App\Http\Controllers\Admin\TrainingSessionController::class, 'checkOutPlayer'])->name('training-sessions.check-out-player');
    Route::get('/training-sessions/{trainingSession}/live-data', [App\Http\Controllers\Admin\TrainingSessionController::class, 'liveData'])->name('training-sessions.live-data');
    Route::get('/training-sessions/{trainingSession}/players-for-attendance', [App\Http\Controllers\Admin\TrainingSessionController::class, 'getPlayersForAttendance'])->name('training-sessions.players-for-attendance');

    // User Approvals
    Route::get('/approvals', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'index'])->name('approvals.index');
    Route::get('/approvals/{user}', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{user}/approve', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{user}/reject', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'reject'])->name('approvals.reject');

    // Document Approvals
    Route::get('/approvals/documents/pending', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'pendingDocuments'])->name('approvals.documents.pending');
    Route::post('/approvals/documents/{document}/approve', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'approveDocument'])->name('approvals.documents.approve');
    Route::post('/approvals/documents/{document}/reject', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'rejectDocument'])->name('approvals.documents.reject');
    Route::post('/approvals/documents/bulk-approve', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'bulkApproveDocuments'])->name('approvals.documents.bulk-approve');
    Route::get('/approvals/documents/{document}/download', [App\Http\Controllers\Admin\AdminUserApprovalController::class, 'downloadDocument'])->name('approvals.documents.download');

    // Image Upload
    Route::get('/image-upload', [App\Http\Controllers\Admin\AdminImageUploadController::class, 'showUploadForm'])->name('image-upload');
    Route::post('/image-upload', [App\Http\Controllers\Admin\AdminImageUploadController::class, 'upload'])->name('image-upload.store');
    Route::get('/images', [App\Http\Controllers\Admin\AdminImageUploadController::class, 'getImages'])->name('images');
    Route::delete('/images', [App\Http\Controllers\Admin\AdminImageUploadController::class, 'deleteImage'])->name('images.delete');

    // Performance Overview
    Route::get('/performance/overview', [App\Http\Controllers\Admin\DashboardController::class, 'performanceOverview'])->name('performance.overview');

    // Compliance Report
    Route::get('/compliance/report', [App\Http\Controllers\Admin\DashboardController::class, 'complianceReport'])->name('compliance.report');
});

// API Routes for AJAX functionality
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/blog', [BlogController::class, 'apiIndex'])->name('blog.index');

    // Football Terminology API
    Route::prefix('terminology')->name('terminology.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\TerminologyController::class, 'index'])->name('index');
        Route::post('/enhance', [App\Http\Controllers\Admin\TerminologyController::class, 'enhance'])->name('enhance');
        Route::get('/search', [App\Http\Controllers\Admin\TerminologyController::class, 'search'])->name('search');
    });

    // AI Insights API Routes
    Route::prefix('ai-insights')->name('ai-insights.')->group(function () {
        // System status
        Route::get('/system/status', [App\Http\Controllers\Api\AiInsightsController::class, 'getSystemStatus'])
            ->name('system.status');

        // Player-specific routes
        Route::prefix('players/{player}')->group(function () {
            // Get all insights for a player
            Route::get('/', [App\Http\Controllers\Api\AiInsightsController::class, 'getPlayerInsights'])
                ->name('players.insights');

            // Get specific insight type
            Route::get('/{type}', [App\Http\Controllers\Api\AiInsightsController::class, 'getInsightByType'])
                ->where('type', implode('|', ['strength', 'development', 'trend', 'style', 'prediction', 'comparison', 'recommendation', 'risk', 'opportunity']))
                ->name('players.insights.type');

            // Trigger insights refresh
            Route::post('/refresh', [App\Http\Controllers\Api\AiInsightsController::class, 'refreshInsights'])
                ->name('players.refresh');

            // Get data freshness status
            Route::get('/freshness/status', [App\Http\Controllers\Api\AiInsightsController::class, 'getDataFreshness'])
                ->name('players.freshness');

            // Get engagement metrics
            Route::get('/metrics/engagement', [App\Http\Controllers\Api\AiInsightsController::class, 'getEngagementMetrics'])
                ->name('players.metrics');

            // Register data source
            Route::post('/data-sources', [App\Http\Controllers\Api\AiInsightsController::class, 'registerDataSource'])
                ->name('players.data-sources');
        });

        // Data source routes
        Route::prefix('data-sources/{source}')->group(function () {
            // Record data upload
            Route::post('/upload', [App\Http\Controllers\Api\AiInsightsController::class, 'recordDataUpload'])
                ->name('data-sources.upload');
        });
    });
});

// Player Portal Routes
Route::middleware(['auth', 'player'])->prefix('player-portal')->name('player.portal.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Player\PlayerPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Player\PlayerPortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Player\PlayerPortalController::class, 'updateProfile'])->name('profile.update');
    Route::get('/programs', [App\Http\Controllers\Player\PlayerPortalController::class, 'programs'])->name('programs');
    Route::get('/training', [App\Http\Controllers\Player\PlayerPortalController::class, 'training'])->name('training');
    Route::get('/schedule', [App\Http\Controllers\Player\PlayerPortalController::class, 'schedule'])->name('schedule');
    Route::get('/resources', [App\Http\Controllers\Player\PlayerPortalController::class, 'resources'])->name('resources');
    Route::get('/orders', [App\Http\Controllers\Player\PlayerPortalController::class, 'orders'])->name('orders');
    Route::get('/payments', [App\Http\Controllers\Player\PlayerPortalController::class, 'payments'])->name('payments');
    Route::get('/communication', [App\Http\Controllers\Player\PlayerPortalController::class, 'communication'])->name('communication');
    Route::get('/support', [App\Http\Controllers\Player\PlayerPortalController::class, 'support'])->name('support');
});

// Partner Routes
Route::middleware(['auth', 'partner'])->prefix('partner')->name('partner.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Partner\PartnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/players', [App\Http\Controllers\Partner\PartnerController::class, 'players'])->name('players');
    Route::get('/players/create', [App\Http\Controllers\Partner\PartnerController::class, 'createPlayer'])->name('player.create');
    Route::post('/players', [App\Http\Controllers\Partner\PartnerController::class, 'storePlayer'])->name('player.store');
    Route::get('/players/{id}', [App\Http\Controllers\Partner\PartnerController::class, 'showPlayer'])->name('player.show');
    Route::get('/players/{id}/edit', [App\Http\Controllers\Partner\PartnerController::class, 'editPlayer'])->name('player.edit');
    Route::put('/players/{id}', [App\Http\Controllers\Partner\PartnerController::class, 'updatePlayer'])->name('player.update');
    Route::get('/analytics', [App\Http\Controllers\Partner\PartnerController::class, 'analytics'])->name('analytics');
    Route::get('/export', [App\Http\Controllers\Partner\PartnerController::class, 'exportPlayers'])->name('export');
});

// Staff Dashboard Routes (Role-based)
// Coach Dashboard - for head-coach, coach, assistant-coach, and partners
Route::middleware(['auth', 'role:coach|assistant-coach|head-coach|partner'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\CoachDashboardController::class, 'index'])->name('dashboard');
    Route::get('/sessions', [App\Http\Controllers\Staff\CoachDashboardController::class, 'trainingSessions'])->name('sessions');
    Route::get('/players', [App\Http\Controllers\Staff\CoachDashboardController::class, 'players'])->name('players');
    Route::get('/player/{player}', [App\Http\Controllers\Staff\CoachDashboardController::class, 'playerProgress'])->name('player.progress');
});

// Team Manager Dashboard
Route::middleware(['auth', 'role:team-manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/registrations', [App\Http\Controllers\Staff\ManagerDashboardController::class, 'registrations'])->name('registrations');
    Route::get('/logistics', [App\Http\Controllers\Staff\ManagerDashboardController::class, 'logistics'])->name('logistics');
});

// Media Officer Dashboard
Route::middleware(['auth', 'role:media-officer'])->prefix('media')->name('media.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\MediaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/blogs', [App\Http\Controllers\Staff\MediaDashboardController::class, 'blogs'])->name('blogs');
    Route::get('/blogs/create', [App\Http\Controllers\Staff\MediaDashboardController::class, 'createBlog'])->name('blogs.create');
    Route::post('/blogs', [App\Http\Controllers\Staff\MediaDashboardController::class, 'storeBlog'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [App\Http\Controllers\Staff\MediaDashboardController::class, 'editBlog'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [App\Http\Controllers\Staff\MediaDashboardController::class, 'updateBlog'])->name('blogs.update');
    Route::get('/gallery', [App\Http\Controllers\Staff\MediaDashboardController::class, 'gallery'])->name('gallery');
});

// Welfare Officer Dashboard
Route::middleware(['auth', 'role:safeguarding-officer'])->prefix('welfare')->name('welfare.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\WelfareDashboardController::class, 'index'])->name('dashboard');
    Route::get('/attention-list', [App\Http\Controllers\Staff\WelfareDashboardController::class, 'attentionList'])->name('attention.list');
    Route::get('/compliance', [App\Http\Controllers\Staff\WelfareDashboardController::class, 'compliance'])->name('compliance');
});

// Finance Officer Dashboard
Route::middleware(['auth', 'role:finance-officer'])->prefix('finance')->name('finance.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'index'])->name('dashboard');
    Route::get('/payments', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'payments'])->name('payments');
    Route::get('/payments/create', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'createPayment'])->name('payments.create');
    Route::post('/payments', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'storePayment'])->name('payments.store');
    Route::get('/payments/{payment}', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'viewPayment'])->name('payments.view');
    Route::get('/payments/{payment}/edit', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'editPayment'])->name('payments.edit');
    Route::put('/payments/{payment}', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'updatePayment'])->name('payments.update');
    Route::delete('/payments/{payment}', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'deletePayment'])->name('payments.delete');
    Route::get('/reports', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'reports'])->name('reports');
    Route::get('/record-payment', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'recordPayment'])->name('record-payment');
    Route::get('/reminders', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'sendReminders'])->name('reminders');
    Route::get('/analytics', [App\Http\Controllers\Staff\FinanceDashboardController::class, 'analytics'])->name('analytics');
});

// Parent Portal Routes
Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Parent\ParentDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Parent\ParentDashboardController::class, 'playerProfile'])->name('profile');
    Route::patch('/profile/update', [App\Http\Controllers\Parent\ParentDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\Parent\ParentDashboardController::class, 'updatePassword'])->name('profile.password');
    Route::get('/finances', [App\Http\Controllers\Parent\ParentDashboardController::class, 'finances'])->name('finances');
    Route::get('/training', [App\Http\Controllers\Parent\ParentDashboardController::class, 'training'])->name('training');
    Route::get('/matches', [App\Http\Controllers\Parent\ParentDashboardController::class, 'matches'])->name('matches');
    Route::get('/media', [App\Http\Controllers\Parent\ParentDashboardController::class, 'media'])->name('media');
    Route::get('/insights', [App\Http\Controllers\Parent\ParentDashboardController::class, 'insights'])->name('insights');
    Route::get('/announcements', [App\Http\Controllers\Parent\ParentDashboardController::class, 'announcements'])->name('announcements');
});

// API Routes
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Player\PlayerPortalController::class, 'dashboard'])->name('dashboard');
});

// Super Admin Routes
Route::middleware(['auth', 'super.admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'dashboard'])->name('dashboard');

    // Organizations Management
    Route::get('/organizations', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'organizations'])->name('organizations.index');
    Route::get('/organizations/create', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'createOrganization'])->name('organizations.create');
    Route::post('/organizations', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'storeOrganization'])->name('organizations.store');
    Route::get('/organizations/{organization}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'showOrganization'])->name('organizations.show');
    Route::get('/organizations/{organization}/edit', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'editOrganization'])->name('organizations.edit');
    Route::put('/organizations/{organization}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'updateOrganization'])->name('organizations.update');
    Route::post('/organizations/{organization}/toggle-status', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'toggleOrganizationStatus'])->name('organizations.toggle-status');

    // Subscription Plans Management
    Route::get('/plans', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'plans'])->name('plans.index');
    Route::get('/plans/create', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'createPlan'])->name('plans.create');
    Route::post('/plans', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'storePlan'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'editPlan'])->name('plans.edit');
    Route::put('/plans/{plan}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'updatePlan'])->name('plans.update');

    // Users Management (Global)
    Route::get('/users', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'users'])->name('users.index');

    // Analytics
    Route::get('/analytics', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'analytics'])->name('analytics');
});

// Organization Admin Routes
Route::middleware(['auth', 'role:org-admin'])->prefix('organization')->name('organization.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Organization\OrganizationDashboardController::class, 'index'])->name('dashboard');

    // Attendance Management
    Route::get('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{attendance}', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/attendance/export', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'showExportPage'])->name('attendance.export.page');
    Route::get('/attendance/export/download', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'export'])->name('attendance.export');
});
