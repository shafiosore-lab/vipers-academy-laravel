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
Route::get('/players/{id}', [PlayerController::class, 'overview'])->name('players.show')->where('id', '[0-9]+');
Route::get('/api/players', [PlayerController::class, 'apiIndex'])->name('players.api');



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

// Gamesuite
Route::get('/gamesuite', [App\Http\Controllers\Website\GamesuiteController::class, 'index'])->name('gamesuite');

// Player Rankings
Route::get('/player-rankings', [PlayerController::class, 'rankings'])->name('player-rankings');

// Statistics Hub
Route::get('/statistics-hub', [StandingsController::class, 'statisticsHub'])->name('statistics-hub');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Donate/Scholarship Support
Route::get('/donate', [App\Http\Controllers\Website\DonateController::class, 'index'])->name('donate');

// Merchandise
Route::get('/merchandise', function() {
    return view('website.merchandise.index');
})->name('merchandise');

// Terms of Service
Route::get('/terms', function() {
    return view('website.terms');
})->name('terms');

// Privacy Policy
Route::get('/privacy', function() {
    return view('website.privacy');
})->name('privacy');

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

    // Eager load roles to prevent N+1 queries
    $user->load('roles');

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

    // Tournament Management (Admin)
    Route::get('/tournaments', [App\Http\Controllers\Admin\AdminTournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [App\Http\Controllers\Admin\AdminTournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [App\Http\Controllers\Admin\AdminTournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}', [App\Http\Controllers\Admin\AdminTournamentController::class, 'show'])->name('tournaments.show');
    Route::get('/tournaments/{tournament}/edit', [App\Http\Controllers\Admin\AdminTournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [App\Http\Controllers\Admin\AdminTournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [App\Http\Controllers\Admin\AdminTournamentController::class, 'destroy'])->name('tournaments.destroy');

    // Tournament Actions
    Route::post('/tournaments/{tournament}/open-registration', [App\Http\Controllers\Admin\AdminTournamentController::class, 'openRegistration'])->name('tournaments.open-registration');
    Route::post('/tournaments/{tournament}/close-registration', [App\Http\Controllers\Admin\AdminTournamentController::class, 'closeRegistration'])->name('tournaments.close-registration');
    Route::post('/tournaments/{tournament}/start', [App\Http\Controllers\Admin\AdminTournamentController::class, 'startTournament'])->name('tournaments.start');
    Route::post('/tournaments/{tournament}/complete', [App\Http\Controllers\Admin\AdminTournamentController::class, 'completeTournament'])->name('tournaments.complete');
    Route::post('/tournaments/{tournament}/generate-fixtures', [App\Http\Controllers\Admin\AdminTournamentController::class, 'generateFixtures'])->name('tournaments.generate-fixtures');
    Route::post('/tournaments/{tournament}/recalculate-standings', [App\Http\Controllers\Admin\AdminTournamentController::class, 'recalculateStandings'])->name('tournaments.recalculate-standings');
    Route::post('/tournaments/{tournament}/unlock-squads', [App\Http\Controllers\Admin\AdminTournamentController::class, 'unlockSquads'])->name('tournaments.unlock-squads');

    // Tournament Standings
    Route::get('/tournaments/{tournament}/standings', [App\Http\Controllers\Admin\AdminTournamentController::class, 'standings'])->name('tournaments.standings.index');

    // Tournament Statistics
    Route::get('/tournaments/{tournament}/statistics', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'index'])->name('tournaments.statistics.index');
    Route::get('/tournaments/{tournament}/statistics/top-scorers', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'topScorers'])->name('tournaments.statistics.top-scorers');
    Route::get('/tournaments/{tournament}/statistics/discipline', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'discipline'])->name('tournaments.statistics.discipline');
    Route::get('/tournaments/{tournament}/statistics/groups', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'groups'])->name('tournaments.statistics.groups');
    Route::get('/tournaments/{tournament}/statistics/rankings', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'rankings'])->name('tournaments.statistics.rankings');
    Route::get('/tournaments/{tournament}/statistics/summary', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'summary'])->name('tournaments.statistics.summary');
    Route::get('/tournaments/{tournament}/statistics/live', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'live'])->name('tournaments.statistics.live');

    // Tournament Teams
    Route::get('/tournaments/{tournament}/teams', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'index'])->name('tournaments.teams.index');
    Route::get('/tournaments/{tournament}/teams/create', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'create'])->name('tournaments.teams.create');
    Route::post('/tournaments/{tournament}/teams', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'store'])->name('tournaments.teams.store');
    Route::get('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'show'])->name('tournaments.teams.show');
    Route::get('/tournaments/{tournament}/teams/{team}/edit', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'edit'])->name('tournaments.teams.edit');
    Route::put('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'update'])->name('tournaments.teams.update');
    Route::delete('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'destroy'])->name('tournaments.teams.destroy');
    Route::post('/tournaments/{tournament}/teams/{team}/approve', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'approve'])->name('tournaments.teams.approve');
    Route::post('/tournaments/{tournament}/teams/{team}/reject', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'reject'])->name('tournaments.teams.reject');
    Route::post('/tournaments/{tournament}/teams/{team}/request-correction', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'requestCorrection'])->name('tournaments.teams.request-correction');
    Route::post('/tournaments/{tournament}/teams/bulk-upload', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'bulkUploadTeams'])->name('tournaments.teams.bulk-upload');
    Route::post('/tournaments/{tournament}/teams/bulk-add', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'bulkAddTeams'])->name('tournaments.teams.bulk-add');
    Route::post('/tournaments/{tournament}/teams/check-existing', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'checkExistingTeams'])->name('tournaments.teams.check-existing');
    Route::get('/tournaments/teams/template', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'downloadTeamTemplate'])->name('tournaments.teams.template');

    // Tournament Squads
    Route::get('/tournaments/{tournament}/teams/{team}/squads', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'index'])->name('tournaments.squads.index');
    Route::get('/tournaments/{tournament}/teams/{team}/squads/create', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'create'])->name('tournaments.squads.create');
    Route::post('/tournaments/{tournament}/teams/{team}/squads', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'store'])->name('tournaments.squads.store');
    Route::put('/tournaments/{tournament}/teams/{team}/squads/{squad}', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'update'])->name('tournaments.squads.update');
    Route::delete('/tournaments/{tournament}/teams/{team}/squads/{squad}', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'destroy'])->name('tournaments.squads.destroy');
    Route::post('/tournaments/{tournament}/teams/{team}/squads/{squad}/verify', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'verify'])->name('tournaments.squads.verify');
    Route::post('/tournaments/{tournament}/teams/{team}/squads/{squad}/reject', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'reject'])->name('tournaments.squads.reject');
    Route::post('/tournaments/{tournament}/teams/{team}/squads/bulk-upload', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'bulkUpload'])->name('tournaments.squads.bulk-upload');
    Route::get('/tournaments/squads/template', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'downloadTemplate'])->name('tournaments.squads.template');
    Route::post('/tournaments/{tournament}/teams/{team}/squads/approve-all', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'approveAll'])->name('tournaments.squads.approve-all');
    Route::get('/tournaments/{tournament}/teams/{team}/squads/export', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'export'])->name('tournaments.squads.export');
    Route::get('/tournaments/{tournament}/teams/{team}/squads/stats', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'stats'])->name('tournaments.squads.stats');

    // Tournament Matches
    Route::get('/tournaments/{tournament}/matches', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'index'])->name('tournaments.matches.index');
    Route::get('/tournaments/{tournament}/matches/create', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'create'])->name('tournaments.matches.create');
    Route::post('/tournaments/{tournament}/matches', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'store'])->name('tournaments.matches.store');
    Route::get('/tournaments/{tournament}/matches/{match}', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'show'])->name('tournaments.matches.show');
    Route::get('/tournaments/{tournament}/matches/{match}/edit', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'edit'])->name('tournaments.matches.edit');
    Route::put('/tournaments/{tournament}/matches/{match}', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'update'])->name('tournaments.matches.update');
    Route::delete('/tournaments/{tournament}/matches/{match}', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'destroy'])->name('tournaments.matches.destroy');
    Route::post('/tournaments/{tournament}/matches/{match}/record-result', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'recordResult'])->name('tournaments.matches.record-result');
    Route::post('/tournaments/{tournament}/matches/{match}/start', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'startMatch'])->name('tournaments.matches.start');
    Route::post('/tournaments/{tournament}/matches/{match}/postpone', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'postpone'])->name('tournaments.matches.postpone');
    Route::post('/tournaments/{tournament}/matches/{match}/cancel', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'cancel'])->name('tournaments.matches.cancel');
    Route::post('/tournaments/{tournament}/matches/{match}/reschedule', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'reschedule'])->name('tournaments.matches.reschedule');
    Route::post('/tournaments/{tournament}/matches/generate-league', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'generateLeagueSchedule'])->name('tournaments.matches.generate-league');
    Route::post('/tournaments/{tournament}/matches/generate-knockout', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'generateKnockoutBracket'])->name('tournaments.matches.generate-knockout');
    Route::post('/tournaments/{tournament}/matches/generate-group-stage', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'generateGroupStageSchedule'])->name('tournaments.matches.generate-group-stage');
    Route::delete('/tournaments/{tournament}/matches/fixtures', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'deleteFixtures'])->name('tournaments.matches.delete-fixtures');
    Route::get('/tournaments/{tournament}/matches/check-conflicts', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'checkConflicts'])->name('tournaments.matches.check-conflicts');

    // Tournament Scheduling
    Route::get('/tournaments/{tournament}/schedule', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'index'])->name('tournaments.schedule.index');
    Route::get('/tournaments/{tournament}/schedule/config', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'config'])->name('tournaments.schedule.config');
    Route::put('/tournaments/{tournament}/schedule/config', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'saveConfig'])->name('tournaments.schedule.config.save');
    Route::get('/tournaments/{tournament}/schedule/time-slots', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'timeSlots'])->name('tournaments.schedule.time-slots');
    Route::post('/tournaments/{tournament}/schedule/auto-schedule', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'autoSchedule'])->name('tournaments.schedule.auto-schedule');
    Route::get('/tournaments/{tournament}/schedule/bulk-schedule', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'bulkSchedule'])->name('tournaments.schedule.bulk-schedule');
    Route::post('/tournaments/{tournament}/schedule/bulk-schedule', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'processBulkSchedule'])->name('tournaments.schedule.bulk-schedule.save');
    Route::get('/tournaments/{tournament}/schedule/constraints', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'checkConstraints'])->name('tournaments.schedule.constraints');
    Route::post('/tournaments/{tournament}/schedule/clear', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'clearSchedule'])->name('tournaments.schedule.clear');
    Route::delete('/tournaments/{tournament}/schedule/matches', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'deleteMatches'])->name('tournaments.schedule.delete-matches');
    Route::get('/tournaments/{tournament}/schedule/slots/available', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'getAvailableSlots'])->name('tournaments.schedule.slots.available');
    Route::post('/tournaments/{tournament}/schedule/slots/validate', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'validateSlot'])->name('tournaments.schedule.slots.validate');

    // Tournament Pools
    Route::get('/tournaments/{tournament}/pools', [App\Http\Controllers\Admin\TournamentPoolController::class, 'index'])->name('tournaments.pools.index');
    Route::get('/tournaments/{tournament}/pools/create', [App\Http\Controllers\Admin\TournamentPoolController::class, 'create'])->name('tournaments.pools.create');
    Route::post('/tournaments/{tournament}/pools', [App\Http\Controllers\Admin\TournamentPoolController::class, 'store'])->name('tournaments.pools.store');
    Route::get('/tournaments/{tournament}/pools/{pool}', [App\Http\Controllers\Admin\TournamentPoolController::class, 'show'])->name('tournaments.pools.show');
    Route::get('/tournaments/{tournament}/pools/{pool}/edit', [App\Http\Controllers\Admin\TournamentPoolController::class, 'edit'])->name('tournaments.pools.edit');
    Route::put('/tournaments/{tournament}/pools/{pool}', [App\Http\Controllers\Admin\TournamentPoolController::class, 'update'])->name('tournaments.pools.update');
    Route::delete('/tournaments/{tournament}/pools/{pool}', [App\Http\Controllers\Admin\TournamentPoolController::class, 'destroy'])->name('tournaments.pools.destroy');
    Route::post('/tournaments/{tournament}/pools/{pool}/assign-team', [App\Http\Controllers\Admin\TournamentPoolController::class, 'assignTeam'])->name('tournaments.pools.assign-team');
    Route::post('/tournaments/{tournament}/pools/{pool}/remove-team', [App\Http\Controllers\Admin\TournamentPoolController::class, 'removeTeam'])->name('tournaments.pools.remove-team');
    Route::post('/tournaments/{tournament}/pools/{pool}/reorder-teams', [App\Http\Controllers\Admin\TournamentPoolController::class, 'reorderTeams'])->name('tournaments.pools.reorder-teams');
    Route::post('/tournaments/{tournament}/pools/move-team', [App\Http\Controllers\Admin\TournamentPoolController::class, 'moveTeam'])->name('tournaments.pools.move-team');
    Route::post('/tournaments/{tournament}/pools/redistribute', [App\Http\Controllers\Admin\TournamentPoolController::class, 'redistribute'])->name('tournaments.pools.redistribute');
    Route::delete('/tournaments/{tournament}/pools/clear', [App\Http\Controllers\Admin\TournamentPoolController::class, 'clearPools'])->name('tournaments.pools.clear');
    Route::post('/tournaments/{tournament}/pools/auto-create', [App\Http\Controllers\Admin\TournamentPoolController::class, 'autoCreatePools'])->name('tournaments.pools.auto-create');

    // Tournament Pool Reshuffle (FIFA-style)
    Route::get('/tournaments/{tournament}/pools/reshuffle', [App\Http\Controllers\Admin\TournamentPoolController::class, 'reshuffle'])->name('tournaments.pools.reshuffle');
    Route::post('/tournaments/{tournament}/pools/reshuffle', [App\Http\Controllers\Admin\TournamentPoolController::class, 'performReshuffle'])->name('tournaments.pools.reshuffle.perform');
    Route::post('/tournaments/{tournament}/pools/reshuffle/reset', [App\Http\Controllers\Admin\TournamentPoolController::class, 'resetReshuffleCount'])->name('tournaments.pools.reshuffle.reset');
    Route::post('/tournaments/{tournament}/pools/update-positions', [App\Http\Controllers\Admin\TournamentPoolController::class, 'updateTeamPositions'])->name('tournaments.pools.update-positions');
    // Match Center AJAX endpoints
    Route::post('/tournaments/{tournament}/match-center/reshuffle', [App\Http\Controllers\Admin\TournamentPoolController::class, 'performReshuffleAjax'])->name('tournaments.match-center.reshuffle');
    Route::post('/tournaments/{tournament}/match-center/reset-reshuffle', [App\Http\Controllers\Admin\TournamentPoolController::class, 'resetReshuffleCountAjax'])->name('tournaments.match-center.reset-reshuffle');

    // Tournament Venues
    Route::get('/tournaments/{tournament}/venues', [App\Http\Controllers\Admin\TournamentVenueController::class, 'index'])->name('tournaments.venues.index');
    Route::get('/tournaments/{tournament}/venues/create', [App\Http\Controllers\Admin\TournamentVenueController::class, 'create'])->name('tournaments.venues.create');
    Route::post('/tournaments/{tournament}/venues', [App\Http\Controllers\Admin\TournamentVenueController::class, 'store'])->name('tournaments.venues.store');
    Route::get('/tournaments/{tournament}/venues/{venue}/edit', [App\Http\Controllers\Admin\TournamentVenueController::class, 'edit'])->name('tournaments.venues.edit');
    Route::put('/tournaments/{tournament}/venues/{venue}', [App\Http\Controllers\Admin\TournamentVenueController::class, 'update'])->name('tournaments.venues.update');
    Route::delete('/tournaments/{tournament}/venues/{venue}', [App\Http\Controllers\Admin\TournamentVenueController::class, 'destroy'])->name('tournaments.venues.destroy');

    // Finance Module (accessible to admin and finance roles)
    Route::prefix('finance')->name('finance.')->group(function () {
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

        // Budget Plans
        Route::get('/budgets', [App\Http\Controllers\Staff\BudgetController::class, 'budgets'])->name('budgets.index');
        Route::get('/budgets/create', [App\Http\Controllers\Staff\BudgetController::class, 'createBudget'])->name('budgets.create');
        Route::post('/budgets', [App\Http\Controllers\Staff\BudgetController::class, 'storeBudget'])->name('budgets.store');
        Route::get('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'showBudget'])->name('budgets.show');
        Route::get('/budgets/{budget}/edit', [App\Http\Controllers\Staff\BudgetController::class, 'editBudget'])->name('budgets.edit');
        Route::put('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'updateBudget'])->name('budgets.update');
        Route::post('/budgets/{budget}/activate', [App\Http\Controllers\Staff\BudgetController::class, 'activateBudget'])->name('budgets.activate');
        Route::post('/budgets/{budget}/close', [App\Http\Controllers\Staff\BudgetController::class, 'closeBudget'])->name('budgets.close');
        Route::delete('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'deleteBudget'])->name('budgets.destroy');
        Route::get('/budgets/comparison', [App\Http\Controllers\Staff\BudgetController::class, 'comparison'])->name('budgets.comparison');
        Route::get('/budgets/summary', [App\Http\Controllers\Staff\BudgetController::class, 'summary'])->name('budgets.summary');

        // Expenses
        Route::get('/expenses', [App\Http\Controllers\Staff\BudgetController::class, 'expenses'])->name('expenses.index');
        Route::get('/expenses/create', [App\Http\Controllers\Staff\BudgetController::class, 'createExpense'])->name('expenses.create');
        Route::post('/expenses', [App\Http\Controllers\Staff\BudgetController::class, 'storeExpense'])->name('expenses.store');
        Route::get('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'showExpense'])->name('expenses.show');
        Route::get('/expenses/{expense}/edit', [App\Http\Controllers\Staff\BudgetController::class, 'editExpense'])->name('expenses.edit');
        Route::put('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'updateExpense'])->name('expenses.update');
        Route::post('/expenses/{expense}/approve', [App\Http\Controllers\Staff\BudgetController::class, 'approveExpense'])->name('expenses.approve');
        Route::post('/expenses/{expense}/reject', [App\Http\Controllers\Staff\BudgetController::class, 'rejectExpense'])->name('expenses.reject');
        Route::post('/expenses/{expense}/mark-paid', [App\Http\Controllers\Staff\BudgetController::class, 'markExpensePaid'])->name('expenses.mark-paid');
        Route::delete('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'deleteExpense'])->name('expenses.destroy');
        Route::get('/expenses/report', [App\Http\Controllers\Staff\BudgetController::class, 'expenseReport'])->name('expenses.report');
    });

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
    Route::get('/standings/export/page', [App\Http\Controllers\Admin\AdminStandingsController::class, 'showExportPage'])->name('standings.export.page');
    Route::get('/standings/export/page/{type}', [App\Http\Controllers\Admin\AdminStandingsController::class, 'showExportPage'])->name('standings.export.page.type');
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

    // Letterhead & Document Generator
    Route::get('/letterhead', [App\Http\Controllers\Admin\LetterheadController::class, 'index'])->name('letterhead.index');
    Route::get('/letterhead/create', [App\Http\Controllers\Admin\LetterheadController::class, 'create'])->name('letterhead.create');
    Route::post('/letterhead', [App\Http\Controllers\Admin\LetterheadController::class, 'store'])->name('letterhead.store');
    Route::get('/letterhead/{letterhead}', [App\Http\Controllers\Admin\LetterheadController::class, 'edit'])->name('letterhead.edit');
    Route::put('/letterhead/{letterhead}', [App\Http\Controllers\Admin\LetterheadController::class, 'update'])->name('letterhead.update');
    Route::delete('/letterhead/{letterhead}', [App\Http\Controllers\Admin\LetterheadController::class, 'destroy'])->name('letterhead.destroy');

    // Organization Documents
    Route::get('/letterhead/documents', [App\Http\Controllers\Admin\LetterheadController::class, 'documents'])->name('letterhead.documents');
    Route::get('/letterhead/document/create', [App\Http\Controllers\Admin\LetterheadController::class, 'documentCreate'])->name('letterhead.document.create');
    Route::post('/letterhead/document', [App\Http\Controllers\Admin\LetterheadController::class, 'documentStore'])->name('letterhead.document.store');
    Route::get('/letterhead/document/{document}/edit', [App\Http\Controllers\Admin\LetterheadController::class, 'documentEdit'])->name('letterhead.document.edit');
    Route::put('/letterhead/document/{document}', [App\Http\Controllers\Admin\LetterheadController::class, 'documentUpdate'])->name('letterhead.document.update');
    Route::delete('/letterhead/document/{document}', [App\Http\Controllers\Admin\LetterheadController::class, 'documentDestroy'])->name('letterhead.document.destroy');
    Route::get('/letterhead/document/{document}/preview', [App\Http\Controllers\Admin\LetterheadController::class, 'documentPreview'])->name('letterhead.document.preview');
    Route::get('/letterhead/document/{document}/download', [App\Http\Controllers\Admin\LetterheadController::class, 'documentDownload'])->name('letterhead.document.download');

    // Blog Management
    Route::get('/blog', [App\Http\Controllers\Admin\AdminBlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [App\Http\Controllers\Admin\AdminBlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [App\Http\Controllers\Admin\AdminBlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}', [App\Http\Controllers\Admin\AdminBlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/{blog}/edit', [App\Http\Controllers\Admin\AdminBlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{blog}', [App\Http\Controllers\Admin\AdminBlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}', [App\Http\Controllers\Admin\AdminBlogController::class, 'destroy'])->name('blog.destroy');




    // Payments Management
    Route::get('/payments', [App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [App\Http\Controllers\Admin\AdminPaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [App\Http\Controllers\Admin\AdminPaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [App\Http\Controllers\Admin\AdminPaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [App\Http\Controllers\Admin\AdminPaymentController::class, 'destroy'])->name('payments.destroy');
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

    // Teams Management - Using AdminTeamController
    Route::get('/teams', [App\Http\Controllers\Admin\AdminTeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [App\Http\Controllers\Admin\AdminTeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [App\Http\Controllers\Admin\AdminTeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}', [App\Http\Controllers\Admin\AdminTeamController::class, 'show'])->name('teams.show');
    Route::get('/teams/{team}/edit', [App\Http\Controllers\Admin\AdminTeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [App\Http\Controllers\Admin\AdminTeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [App\Http\Controllers\Admin\AdminTeamController::class, 'destroy'])->name('teams.destroy');

    // Bulk SMS Management
    Route::get('/sms', [App\Http\Controllers\Admin\AdminSmsController::class, 'index'])->name('sms.index');
    Route::post('/sms/send', [App\Http\Controllers\Admin\AdminSmsController::class, 'send'])->name('sms.send');
    Route::post('/sms/send-all-players', [App\Http\Controllers\Admin\AdminSmsController::class, 'sendToAllPlayers'])->name('sms.sendAllPlayers');
    Route::get('/sms/history', [App\Http\Controllers\Admin\AdminSmsController::class, 'history'])->name('sms.history');

    // WhatsApp Management
    Route::get('/whatsapp', [App\Http\Controllers\Admin\AdminWhatsAppController::class, 'index'])->name('whatsapp.index');
    Route::post('/whatsapp/send', [App\Http\Controllers\Admin\AdminWhatsAppController::class, 'send'])->name('whatsapp.send');
    Route::post('/whatsapp/send-all-players', [App\Http\Controllers\Admin\AdminWhatsAppController::class, 'sendToAllPlayers'])->name('whatsapp.sendAllPlayers');
    Route::get('/whatsapp/templates', [App\Http\Controllers\Admin\AdminWhatsAppController::class, 'templates'])->name('whatsapp.templates');
    Route::get('/whatsapp/history', [App\Http\Controllers\Admin\AdminWhatsAppController::class, 'history'])->name('whatsapp.history');

    // Message Gateway Settings (Unified)
    Route::get('/messaging/settings', [App\Http\Controllers\Admin\MessageGatewayController::class, 'index'])->name('messaging.settings');
    Route::get('/messaging/quick', [App\Http\Controllers\Admin\MessageGatewayController::class, 'quick'])->name('messaging.quick');
    Route::post('/messaging/send', [App\Http\Controllers\Admin\MessageGatewayController::class, 'send'])->name('messaging.send');
    Route::put('/messaging/gateway/{gateway}', [App\Http\Controllers\Admin\MessageGatewayController::class, 'update'])->name('messaging.gateway.update');
    Route::post('/messaging/gateway/{gateway}/toggle', [App\Http\Controllers\Admin\MessageGatewayController::class, 'toggleStatus'])->name('messaging.gateway.toggle');
    Route::post('/messaging/gateway/{gateway}/set-primary', [App\Http\Controllers\Admin\MessageGatewayController::class, 'setPrimary'])->name('messaging.gateway.setPrimary');
    Route::post('/messaging/gateway/test', [App\Http\Controllers\Admin\MessageGatewayController::class, 'testMessage'])->name('messaging.gateway.test');
    Route::post('/messaging/gateway', [App\Http\Controllers\Admin\MessageGatewayController::class, 'store'])->name('messaging.gateway.store');
    Route::delete('/messaging/gateway/{gateway}', [App\Http\Controllers\Admin\MessageGatewayController::class, 'destroy'])->name('messaging.gateway.destroy');


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

    // Equipment Management Routes (accessible by admin/super-admin)
    Route::get('/equipment/categories', [App\Http\Controllers\Staff\EquipmentController::class, 'categories'])->name('equipment.categories');
    Route::post('/equipment/categories', [App\Http\Controllers\Staff\EquipmentController::class, 'storeCategory'])->name('equipment.categories.store');
    Route::put('/equipment/categories/{category}', [App\Http\Controllers\Staff\EquipmentController::class, 'updateCategory'])->name('equipment.categories.update');
    Route::delete('/equipment/categories/{category}', [App\Http\Controllers\Staff\EquipmentController::class, 'destroyCategory'])->name('equipment.categories.destroy');

    Route::get('/equipment/inventory', [App\Http\Controllers\Staff\EquipmentController::class, 'inventory'])->name('equipment.inventory');
    Route::post('/equipment/inventory', [App\Http\Controllers\Staff\EquipmentController::class, 'storeEquipment'])->name('equipment.inventory.store');
    Route::put('/equipment/inventory/{equipment}', [App\Http\Controllers\Staff\EquipmentController::class, 'updateEquipment'])->name('equipment.inventory.update');
    Route::delete('/equipment/inventory/{equipment}', [App\Http\Controllers\Staff\EquipmentController::class, 'destroyEquipment'])->name('equipment.inventory.destroy');

    Route::get('/equipment/distribution', [App\Http\Controllers\Staff\EquipmentController::class, 'distribution'])->name('equipment.distribution');
    Route::post('/equipment/distribution', [App\Http\Controllers\Staff\EquipmentController::class, 'storeDistribution'])->name('equipment.distribution.store');
    Route::post('/equipment/distribution/{distribution}/return', [App\Http\Controllers\Staff\EquipmentController::class, 'returnEquipment'])->name('equipment.distribution.return');

    Route::get('/equipment/compliance', [App\Http\Controllers\Staff\EquipmentController::class, 'compliance'])->name('equipment.compliance');
    Route::post('/equipment/compliance/report', [App\Http\Controllers\Staff\EquipmentController::class, 'generateComplianceReport'])->name('equipment.compliance.report');

    // Page Content Management
    Route::get('/page-content', [App\Http\Controllers\Admin\AdminPageContentController::class, 'index'])->name('page-content.index');
    Route::get('/page-content/{page}', [App\Http\Controllers\Admin\AdminPageContentController::class, 'showPage'])->name('page-content.show');
    Route::get('/page-content/{page}/{section}/edit', [App\Http\Controllers\Admin\AdminPageContentController::class, 'editSection'])->name('page-content.edit');
    Route::put('/page-content/{page}/{section}', [App\Http\Controllers\Admin\AdminPageContentController::class, 'update'])->name('page-content.update');

    // Leaders Management (Meet Our Leaders page)
    Route::get('/leaders', [App\Http\Controllers\Admin\AdminLeaderController::class, 'index'])->name('leaders.index');
    Route::get('/leaders/create', [App\Http\Controllers\Admin\AdminLeaderController::class, 'create'])->name('leaders.create');
    Route::post('/leaders', [App\Http\Controllers\Admin\AdminLeaderController::class, 'store'])->name('leaders.store');
    Route::get('/leaders/{leader}', [App\Http\Controllers\Admin\AdminLeaderController::class, 'show'])->name('leaders.show');
    Route::get('/leaders/{leader}/edit', [App\Http\Controllers\Admin\AdminLeaderController::class, 'edit'])->name('leaders.edit');
    Route::put('/leaders/{leader}', [App\Http\Controllers\Admin\AdminLeaderController::class, 'update'])->name('leaders.update');
    Route::delete('/leaders/{leader}', [App\Http\Controllers\Admin\AdminLeaderController::class, 'destroy'])->name('leaders.destroy');
    Route::post('/leaders/{leader}/toggle-status', [App\Http\Controllers\Admin\AdminLeaderController::class, 'toggleStatus'])->name('leaders.toggle-status');
    Route::post('/leaders/reorder', [App\Http\Controllers\Admin\AdminLeaderController::class, 'reorder'])->name('leaders.reorder');

    // Journey section - Add/Delete entries
    Route::post('/page-content/journey/add', [App\Http\Controllers\Admin\AdminPageContentController::class, 'addJourneyEntry'])->name('page-content.journey.add');
    Route::get('/page-content/journey/{id}/delete', [App\Http\Controllers\Admin\AdminPageContentController::class, 'deleteJourneyEntry'])->name('page-content.journey.delete');

    // Values section - Add/Delete entries
    Route::post('/page-content/values/add', [App\Http\Controllers\Admin\AdminPageContentController::class, 'addValueEntry'])->name('page-content.values.add');
    Route::get('/page-content/values/{id}/delete', [App\Http\Controllers\Admin\AdminPageContentController::class, 'deleteValueEntry'])->name('page-content.values.delete');
});

// API Routes for AJAX functionality
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/blog', [BlogController::class, 'apiIndex'])->name('blog.index');

    // Players API for filtering and search
    Route::get('/players', [App\Http\Controllers\Website\PlayerController::class, 'apiIndex'])->name('players.index');

    // Staff Management - Get available roles based on user permission level
    Route::get('/staff/roles', [App\Http\Controllers\Admin\AdminStaffController::class, 'getAvailableRoles'])->name('staff.roles');

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

    // Attendance Management (Coach access)
    Route::get('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{attendance}', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/attendance/{attendance}/check-in', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/{attendance}/check-out', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'checkOut'])->name('attendance.check-out');

    // Training Sessions Management (Coach access)
    Route::get('/training-sessions', [App\Http\Controllers\Admin\TrainingSessionController::class, 'index'])->name('training-sessions.index');
    Route::get('/training-sessions/{trainingSession}', [App\Http\Controllers\Admin\TrainingSessionController::class, 'show'])->name('training-sessions.show');
    Route::post('/training-sessions/{trainingSession}/start', [App\Http\Controllers\Admin\TrainingSessionController::class, 'start'])->name('training-sessions.start');
    Route::post('/training-sessions/{trainingSession}/end', [App\Http\Controllers\Admin\TrainingSessionController::class, 'end'])->name('training-sessions.end');
    Route::post('/training-sessions/{trainingSession}/admit-player', [App\Http\Controllers\Admin\TrainingSessionController::class, 'admitPlayer'])->name('training-sessions.admit-player');
    Route::post('/training-sessions/{trainingSession}/check-out-player', [App\Http\Controllers\Admin\TrainingSessionController::class, 'checkOutPlayer'])->name('training-sessions.check-out-player');
    Route::get('/training-sessions/{trainingSession}/players-for-attendance', [App\Http\Controllers\Admin\TrainingSessionController::class, 'getPlayersForAttendance'])->name('training-sessions.players-for-attendance');
});

// Team Manager Dashboard
Route::middleware(['auth', 'role:team-manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/registrations', [App\Http\Controllers\Staff\ManagerDashboardController::class, 'registrations'])->name('registrations');
    Route::get('/logistics', [App\Http\Controllers\Staff\ManagerDashboardController::class, 'logistics'])->name('logistics');

    // Equipment Management Routes
    Route::get('/equipment/categories', [App\Http\Controllers\Staff\EquipmentController::class, 'categories'])->name('equipment.categories');
    Route::post('/equipment/categories', [App\Http\Controllers\Staff\EquipmentController::class, 'storeCategory'])->name('equipment.categories.store');
    Route::put('/equipment/categories/{category}', [App\Http\Controllers\Staff\EquipmentController::class, 'updateCategory'])->name('equipment.categories.update');
    Route::delete('/equipment/categories/{category}', [App\Http\Controllers\Staff\EquipmentController::class, 'destroyCategory'])->name('equipment.categories.destroy');

    Route::get('/equipment/inventory', [App\Http\Controllers\Staff\EquipmentController::class, 'inventory'])->name('equipment.inventory');
    Route::post('/equipment/inventory', [App\Http\Controllers\Staff\EquipmentController::class, 'storeEquipment'])->name('equipment.inventory.store');
    Route::put('/equipment/inventory/{equipment}', [App\Http\Controllers\Staff\EquipmentController::class, 'updateEquipment'])->name('equipment.inventory.update');
    Route::delete('/equipment/inventory/{equipment}', [App\Http\Controllers\Staff\EquipmentController::class, 'destroyEquipment'])->name('equipment.inventory.destroy');

    Route::get('/equipment/distribution', [App\Http\Controllers\Staff\EquipmentController::class, 'distribution'])->name('equipment.distribution');
    Route::post('/equipment/distribution', [App\Http\Controllers\Staff\EquipmentController::class, 'storeDistribution'])->name('equipment.distribution.store');
    Route::post('/equipment/distribution/{distribution}/return', [App\Http\Controllers\Staff\EquipmentController::class, 'returnEquipment'])->name('equipment.distribution.return');

    Route::get('/equipment/compliance', [App\Http\Controllers\Staff\EquipmentController::class, 'compliance'])->name('equipment.compliance');
    Route::post('/equipment/compliance/report', [App\Http\Controllers\Staff\EquipmentController::class, 'generateComplianceReport'])->name('equipment.compliance.report');
});

// Media Officer Dashboard
Route::middleware(['auth', 'role:media-officer'])->prefix('media')->name('media.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\MediaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/blogs', [App\Http\Controllers\Staff\MediaDashboardController::class, 'blogs'])->name('blogs');
    Route::get('/blogs/create', [App\Http\Controllers\Staff\MediaDashboardController::class, 'createBlog'])->name('blogs.create');
    Route::post('/blogs', [App\Http\Controllers\Staff\MediaDashboardController::class, 'storeBlog'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [App\Http\Controllers\Staff\MediaDashboardController::class, 'editBlog'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [App\Http\Controllers\Staff\MediaDashboardController::class, 'updateBlog'])->name('blogs.update');
});

// Welfare Officer Dashboard
Route::middleware(['auth', 'role:safeguarding-officer'])->prefix('welfare')->name('welfare.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Staff\WelfareDashboardController::class, 'index'])->name('dashboard');
    Route::get('/attention-list', [App\Http\Controllers\Staff\WelfareDashboardController::class, 'attentionList'])->name('attention.list');
    Route::get('/compliance', [App\Http\Controllers\Staff\WelfareDashboardController::class, 'compliance'])->name('compliance');
});

// Finance Officer Dashboard
Route::middleware(['auth', 'role:finance-officer|finance-admin|operations-admin'])->prefix('finance')->name('finance.')->group(function () {
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

    // Budget Plans
    Route::get('/budgets', [App\Http\Controllers\Staff\BudgetController::class, 'budgets'])->name('budgets.index');
    Route::get('/budgets/create', [App\Http\Controllers\Staff\BudgetController::class, 'createBudget'])->name('budgets.create');
    Route::post('/budgets', [App\Http\Controllers\Staff\BudgetController::class, 'storeBudget'])->name('budgets.store');
    Route::get('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'showBudget'])->name('budgets.show');
    Route::get('/budgets/{budget}/edit', [App\Http\Controllers\Staff\BudgetController::class, 'editBudget'])->name('budgets.edit');
    Route::put('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'updateBudget'])->name('budgets.update');
    Route::post('/budgets/{budget}/activate', [App\Http\Controllers\Staff\BudgetController::class, 'activateBudget'])->name('budgets.activate');
    Route::post('/budgets/{budget}/close', [App\Http\Controllers\Staff\BudgetController::class, 'closeBudget'])->name('budgets.close');
    Route::delete('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'deleteBudget'])->name('budgets.destroy');

    // Budget Comparison
    Route::get('/budgets/comparison', [App\Http\Controllers\Staff\BudgetController::class, 'comparison'])->name('budgets.comparison');

    // Budget Summary
    Route::get('/budgets/summary', [App\Http\Controllers\Staff\BudgetController::class, 'summary'])->name('budgets.summary');

    // Expenses
    Route::get('/expenses', [App\Http\Controllers\Staff\BudgetController::class, 'expenses'])->name('expenses.index');
    Route::get('/expenses/create', [App\Http\Controllers\Staff\BudgetController::class, 'createExpense'])->name('expenses.create');
    Route::post('/expenses', [App\Http\Controllers\Staff\BudgetController::class, 'storeExpense'])->name('expenses.store');
    Route::get('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'showExpense'])->name('expenses.show');
    Route::get('/expenses/{expense}/edit', [App\Http\Controllers\Staff\BudgetController::class, 'editExpense'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'updateExpense'])->name('expenses.update');
    Route::post('/expenses/{expense}/approve', [App\Http\Controllers\Staff\BudgetController::class, 'approveExpense'])->name('expenses.approve');
    Route::post('/expenses/{expense}/reject', [App\Http\Controllers\Staff\BudgetController::class, 'rejectExpense'])->name('expenses.reject');
    Route::post('/expenses/{expense}/mark-paid', [App\Http\Controllers\Staff\BudgetController::class, 'markExpensePaid'])->name('expenses.mark-paid');
    Route::delete('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'deleteExpense'])->name('expenses.destroy');

    // Expense Reports
    Route::get('/expenses/report', [App\Http\Controllers\Staff\BudgetController::class, 'expenseReport'])->name('expenses.report');
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

    // Finance Module (accessible to super-admin)
    Route::prefix('finance')->name('finance.')->group(function () {
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

        // Budget Plans
        Route::get('/budgets', [App\Http\Controllers\Staff\BudgetController::class, 'budgets'])->name('budgets.index');
        Route::get('/budgets/create', [App\Http\Controllers\Staff\BudgetController::class, 'createBudget'])->name('budgets.create');
        Route::post('/budgets', [App\Http\Controllers\Staff\BudgetController::class, 'storeBudget'])->name('budgets.store');
        Route::get('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'showBudget'])->name('budgets.show');
        Route::get('/budgets/{budget}/edit', [App\Http\Controllers\Staff\BudgetController::class, 'editBudget'])->name('budgets.edit');
        Route::put('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'updateBudget'])->name('budgets.update');
        Route::post('/budgets/{budget}/activate', [App\Http\Controllers\Staff\BudgetController::class, 'activateBudget'])->name('budgets.activate');
        Route::post('/budgets/{budget}/close', [App\Http\Controllers\Staff\BudgetController::class, 'closeBudget'])->name('budgets.close');
        Route::delete('/budgets/{budget}', [App\Http\Controllers\Staff\BudgetController::class, 'deleteBudget'])->name('budgets.destroy');
        Route::get('/budgets/comparison', [App\Http\Controllers\Staff\BudgetController::class, 'comparison'])->name('budgets.comparison');
        Route::get('/budgets/summary', [App\Http\Controllers\Staff\BudgetController::class, 'summary'])->name('budgets.summary');

        // Expenses
        Route::get('/expenses', [App\Http\Controllers\Staff\BudgetController::class, 'expenses'])->name('expenses.index');
        Route::get('/expenses/create', [App\Http\Controllers\Staff\BudgetController::class, 'createExpense'])->name('expenses.create');
        Route::post('/expenses', [App\Http\Controllers\Staff\BudgetController::class, 'storeExpense'])->name('expenses.store');
        Route::get('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'showExpense'])->name('expenses.show');
        Route::get('/expenses/{expense}/edit', [App\Http\Controllers\Staff\BudgetController::class, 'editExpense'])->name('expenses.edit');
        Route::put('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'updateExpense'])->name('expenses.update');
        Route::post('/expenses/{expense}/approve', [App\Http\Controllers\Staff\BudgetController::class, 'approveExpense'])->name('expenses.approve');
        Route::post('/expenses/{expense}/reject', [App\Http\Controllers\Staff\BudgetController::class, 'rejectExpense'])->name('expenses.reject');
        Route::post('/expenses/{expense}/mark-paid', [App\Http\Controllers\Staff\BudgetController::class, 'markExpensePaid'])->name('expenses.mark-paid');
        Route::delete('/expenses/{expense}', [App\Http\Controllers\Staff\BudgetController::class, 'deleteExpense'])->name('expenses.destroy');
        Route::get('/expenses/report', [App\Http\Controllers\Staff\BudgetController::class, 'expenseReport'])->name('expenses.report');
    });

    // Attendance Management (Super Admin access)
    Route::get('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{attendance}', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/attendance/export', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'showExportPage'])->name('attendance.export.page');
    Route::get('/attendance/export/download', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'export'])->name('attendance.export');

    // Organizations Management
    Route::get('/organizations', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'organizations'])->name('organizations.index');
    Route::get('/organizations/create', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'createOrganization'])->name('organizations.create');
    Route::post('/organizations', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'storeOrganization'])->name('organizations.store');
    Route::get('/organizations/{organization}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'showOrganization'])->name('organizations.show');
    Route::get('/organizations/{organization}/edit', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'editOrganization'])->name('organizations.edit');
    Route::put('/organizations/{organization}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'updateOrganization'])->name('organizations.update');
    Route::delete('/organizations/{organization}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'destroyOrganization'])->name('organizations.destroy');
    Route::post('/organizations/{organization}/toggle-status', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'toggleOrganizationStatus'])->name('organizations.toggle-status');

    // Subscription Plans Management
    Route::get('/plans', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'plans'])->name('plans.index');
    Route::get('/plans/create', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'createPlan'])->name('plans.create');
    Route::post('/plans', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'storePlan'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'editPlan'])->name('plans.edit');
    Route::put('/plans/{plan}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'updatePlan'])->name('plans.update');
    Route::delete('/plans/{plan}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'destroyPlan'])->name('plans.destroy');

    // Role Management Routes
    Route::get('/roles', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'index'])->name('roles.index');
    Route::get('/roles/hierarchy', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'hierarchy'])->name('roles.hierarchy');
    Route::get('/roles/create', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'create'])->name('roles.create');
    Route::post('/roles', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'destroy'])->name('roles.destroy');

    // Hybrid Roles
    Route::get('/roles/hybrid/create', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'createHybrid'])->name('roles.hybrid.create');
    Route::post('/roles/hybrid', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'createHybrid'])->name('roles.hybrid.store');

    // Role Templates
    Route::get('/roles/templates', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'templates'])->name('roles.templates.index');
    Route::get('/roles/templates/create', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'createTemplate'])->name('roles.templates.create');
    Route::post('/roles/templates', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'storeTemplate'])->name('roles.templates.store');

    // Role Requests
    Route::get('/roles/requests', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'requests'])->name('roles.requests.index');
    Route::post('/roles/requests/{request}/approve', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'approveRequest'])->name('roles.requests.approve');
    Route::post('/roles/requests/{request}/reject', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'rejectRequest'])->name('roles.requests.reject');

    // Audit Logs
    Route::get('/roles/audit', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'auditLogs'])->name('roles.audit');

    // Module Permissions
    Route::get('/roles/modules', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'modulePermissions'])->name('roles.modules.index');
    Route::post('/roles/modules', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'storeModulePermission'])->name('roles.modules.store');

    // API Endpoints
    Route::get('/roles/tree', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'getRoleTree'])->name('roles.tree');
    Route::get('/roles/{role}/permissions', [App\Http\Controllers\SuperAdmin\RoleManagementController::class, 'getRolePermissions'])->name('roles.permissions');

    // Users Management (Global)
    Route::get('/users', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'users'])->name('users.index');
    Route::delete('/users/{user}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'destroyUser'])->name('users.destroy');

    // Analytics
    Route::get('/analytics', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'analytics'])->name('analytics');

    // Tournament Management (Super Admin)
    Route::get('/tournaments/overview', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'overview'])->name('tournaments.overview');
    Route::get('/tournaments', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'show'])->name('tournaments.show');
    Route::get('/tournaments/{tournament}/edit', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'destroy'])->name('tournaments.destroy');

    // Tournament Actions
    Route::post('/tournaments/{tournament}/open-registration', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'openRegistration'])->name('tournaments.open-registration');
    Route::post('/tournaments/{tournament}/close-registration', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'closeRegistration'])->name('tournaments.close-registration');
    Route::post('/tournaments/{tournament}/start', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'startTournament'])->name('tournaments.start');
    Route::post('/tournaments/{tournament}/complete', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'completeTournament'])->name('tournaments.complete');
    Route::post('/tournaments/{tournament}/cancel', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'cancelTournament'])->name('tournaments.cancel');

    // Tournament Teams
    Route::get('/tournaments/{tournament}/teams', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'teams'])->name('tournaments.teams.index');
    Route::get('/tournaments/{tournament}/teams/create', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'createTeam'])->name('tournaments.teams.create');
    Route::post('/tournaments/{tournament}/teams', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'storeTeam'])->name('tournaments.teams.store');
    Route::get('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'teamPlayers'])->name('tournaments.teams.show');
    Route::get('/tournaments/{tournament}/teams/{team}/edit', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'edit'])->name('tournaments.teams.edit');
    Route::put('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'update'])->name('tournaments.teams.update');
    Route::delete('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'destroy'])->name('tournaments.teams.destroy');
    Route::get('/tournaments/{tournament}/teams/{team}/players', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'teamPlayers'])->name('tournaments.teams.players.index');
    Route::post('/tournaments/{tournament}/teams/{team}/approve', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'approveTeam'])->name('tournaments.teams.approve');
    Route::post('/tournaments/{tournament}/teams/{team}/reject', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'rejectTeam'])->name('tournaments.teams.reject');
    Route::get('/tournaments/teams/template', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'downloadTeamTemplate'])->name('tournaments.teams.template');
    Route::post('/tournaments/{tournament}/teams/bulk-upload', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'bulkUploadTeams'])->name('tournaments.teams.bulk-upload');
    Route::post('/tournaments/{tournament}/teams/bulk-add', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'bulkAddTeams'])->name('tournaments.teams.bulk-add');
    Route::post('/tournaments/{tournament}/teams/check-existing', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'checkExistingTeams'])->name('tournaments.teams.check-existing');

    // Tournament Players
    Route::get('/tournaments/{tournament}/players', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'players'])->name('tournaments.players.index');
    Route::post('/tournaments/{tournament}/players/{squad}/approve', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'approvePlayer'])->name('tournaments.players.approve');
    Route::post('/tournaments/{tournament}/players/{squad}/reject', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'rejectPlayer'])->name('tournaments.players.reject');
    Route::delete('/tournaments/{tournament}/players/{squad}', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'removePlayer'])->name('tournaments.players.destroy');

    // Tournament Matches
    Route::get('/tournaments/{tournament}/matches', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'matches'])->name('tournaments.matches.index');

    // Tournament Standings
    Route::get('/tournaments/{tournament}/standings', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'standings'])->name('tournaments.standings.index');

    // Tournament Statistics
    Route::get('/tournaments/{tournament}/statistics', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'index'])->name('tournaments.statistics.index');
    Route::get('/tournaments/{tournament}/statistics/top-scorers', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'topScorers'])->name('tournaments.statistics.top-scorers');
    Route::get('/tournaments/{tournament}/statistics/discipline', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'discipline'])->name('tournaments.statistics.discipline');
    Route::get('/tournaments/{tournament}/statistics/groups', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'groups'])->name('tournaments.statistics.groups');
    Route::get('/tournaments/{tournament}/statistics/rankings', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'rankings'])->name('tournaments.statistics.rankings');
    Route::get('/tournaments/{tournament}/statistics/summary', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'summary'])->name('tournaments.statistics.summary');
    Route::get('/tournaments/{tournament}/statistics/live', [App\Http\Controllers\Admin\TournamentStatisticsController::class, 'live'])->name('tournaments.statistics.live');

    // Tournament Pool Reshuffle (FIFA-style)
    Route::get('/tournaments/{tournament}/pools/reshuffle', [App\Http\Controllers\Admin\TournamentPoolController::class, 'reshuffle'])->name('tournaments.pools.reshuffle');
    Route::post('/tournaments/{tournament}/pools/reshuffle', [App\Http\Controllers\Admin\TournamentPoolController::class, 'performReshuffle'])->name('tournaments.pools.reshuffle.perform');
    Route::post('/tournaments/{tournament}/pools/reshuffle/reset', [App\Http\Controllers\Admin\TournamentPoolController::class, 'resetReshuffleCount'])->name('tournaments.pools.reshuffle.reset');
    Route::post('/tournaments/{tournament}/pools/update-positions', [App\Http\Controllers\Admin\TournamentPoolController::class, 'updateTeamPositions'])->name('tournaments.pools.update-positions');
    // Match Center AJAX endpoints (Super Admin)
    Route::post('/tournaments/{tournament}/match-center/reshuffle', [App\Http\Controllers\Admin\TournamentPoolController::class, 'performReshuffleAjax'])->name('tournaments.match-center.reshuffle');
    Route::post('/tournaments/{tournament}/match-center/reset-reshuffle', [App\Http\Controllers\Admin\TournamentPoolController::class, 'resetReshuffleCountAjax'])->name('tournaments.match-center.reset-reshuffle');

    // Tournament Fixtures
    Route::post('/tournaments/{tournament}/generate-fixtures', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'generateFixtures'])->name('tournaments.generate-fixtures');

    // Tournament Scheduling (Super Admin)
    Route::get('/tournaments/{tournament}/schedule', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'index'])->name('tournaments.schedule.index');
    Route::get('/tournaments/{tournament}/schedule/config', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'config'])->name('tournaments.schedule.config');
    Route::put('/tournaments/{tournament}/schedule/config', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'saveConfig'])->name('tournaments.schedule.config.save');
    Route::get('/tournaments/{tournament}/schedule/time-slots', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'timeSlots'])->name('tournaments.schedule.time-slots');
    Route::post('/tournaments/{tournament}/schedule/auto-schedule', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'autoSchedule'])->name('tournaments.schedule.auto-schedule');
    Route::get('/tournaments/{tournament}/schedule/bulk-schedule', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'bulkSchedule'])->name('tournaments.schedule.bulk-schedule');
    Route::post('/tournaments/{tournament}/schedule/bulk-schedule', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'processBulkSchedule'])->name('tournaments.schedule.bulk-schedule.save');
    Route::get('/tournaments/{tournament}/schedule/constraints', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'checkConstraints'])->name('tournaments.schedule.constraints');
    Route::post('/tournaments/{tournament}/schedule/clear', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'clearSchedule'])->name('tournaments.schedule.clear');
    Route::delete('/tournaments/{tournament}/schedule/matches', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'deleteMatches'])->name('tournaments.schedule.delete-matches');
    Route::get('/tournaments/{tournament}/schedule/slots/available', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'getAvailableSlots'])->name('tournaments.schedule.slots.available');
    Route::post('/tournaments/{tournament}/schedule/slots/validate', [App\Http\Controllers\Admin\TournamentScheduleController::class, 'validateSlot'])->name('tournaments.schedule.slots.validate');

    // Close Registration and Generate Fixtures (one-click)
    Route::post('/tournaments/{tournament}/close-and-generate', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'closeAndGenerateFixtures'])->name('tournaments.close-and-generate');

    // Tournament Visibility
    Route::post('/tournaments/{tournament}/toggle-visibility', [App\Http\Controllers\SuperAdmin\SuperAdminTournamentController::class, 'toggleVisibility'])->name('tournaments.toggle-visibility');

    // Training Sessions (Super Admin access)
    Route::resource('training-sessions', App\Http\Controllers\Admin\TrainingSessionController::class);
    Route::post('/training-sessions/{trainingSession}/start', [App\Http\Controllers\Admin\TrainingSessionController::class, 'start'])->name('training-sessions.start');
    Route::post('/training-sessions/{trainingSession}/end', [App\Http\Controllers\Admin\TrainingSessionController::class, 'end'])->name('training-sessions.end');
    Route::post('/training-sessions/{trainingSession}/admit-player', [App\Http\Controllers\Admin\TrainingSessionController::class, 'admitPlayer'])->name('training-sessions.admit-player');
    Route::post('/training-sessions/{trainingSession}/check-out-player', [App\Http\Controllers\Admin\TrainingSessionController::class, 'checkOutPlayer'])->name('training-sessions.check-out-player');
    Route::get('/training-sessions/{trainingSession}/live-data', [App\Http\Controllers\Admin\TrainingSessionController::class, 'liveData'])->name('training-sessions.live-data');
    Route::get('/training-sessions/{trainingSession}/players-for-attendance', [App\Http\Controllers\Admin\TrainingSessionController::class, 'getPlayersForAttendance'])->name('training-sessions.players-for-attendance');

    // Page Content Management (Super Admin)
    Route::get('/page-content', [App\Http\Controllers\SuperAdmin\SuperAdminPageContentController::class, 'index'])->name('page-content.index');
    Route::get('/page-content/{page}', [App\Http\Controllers\SuperAdmin\SuperAdminPageContentController::class, 'show'])->name('page-content.show');
    Route::get('/page-content/{page}/{section}/edit', [App\Http\Controllers\SuperAdmin\SuperAdminPageContentController::class, 'edit'])->name('page-content.edit');
    Route::put('/page-content/{page}/{section}', [App\Http\Controllers\SuperAdmin\SuperAdminPageContentController::class, 'update'])->name('page-content.update');
    Route::post('/page-content/journey/add', [App\Http\Controllers\SuperAdmin\SuperAdminPageContentController::class, 'addJourneyEntry'])->name('page-content.journey.add');
    Route::get('/page-content/journey/{id}/delete', [App\Http\Controllers\SuperAdmin\SuperAdminPageContentController::class, 'deleteJourneyEntry'])->name('page-content.journey.delete');
    Route::post('/page-content/values/add', [App\Http\Controllers\SuperAdmin\SuperAdminPageContentController::class, 'addValueEntry'])->name('page-content.values.add');
    Route::get('/page-content/values/{id}/delete', [App\Http\Controllers\SuperAdmin\SuperAdminPageContentController::class, 'deleteValueEntry'])->name('page-content.values.delete');

    // Letterhead Management (Super Admin)
    Route::get('/letterhead', [App\Http\Controllers\Admin\LetterheadController::class, 'index'])->name('letterhead.index');
    Route::get('/letterhead/create', [App\Http\Controllers\Admin\LetterheadController::class, 'create'])->name('letterhead.create');
    Route::post('/letterhead', [App\Http\Controllers\Admin\LetterheadController::class, 'store'])->name('letterhead.store');
    Route::get('/letterhead/{letterhead}', [App\Http\Controllers\Admin\LetterheadController::class, 'edit'])->name('letterhead.edit');
    Route::put('/letterhead/{letterhead}', [App\Http\Controllers\Admin\LetterheadController::class, 'update'])->name('letterhead.update');
    Route::delete('/letterhead/{letterhead}', [App\Http\Controllers\Admin\LetterheadController::class, 'destroy'])->name('letterhead.destroy');
    Route::get('/letterhead/documents', [App\Http\Controllers\Admin\LetterheadController::class, 'documents'])->name('letterhead.documents');
    Route::get('/letterhead/document/create', [App\Http\Controllers\Admin\LetterheadController::class, 'documentCreate'])->name('letterhead.document.create');
    Route::post('/letterhead/document', [App\Http\Controllers\Admin\LetterheadController::class, 'documentStore'])->name('letterhead.document.store');
    Route::get('/letterhead/document/{document}/edit', [App\Http\Controllers\Admin\LetterheadController::class, 'documentEdit'])->name('letterhead.document.edit');
    Route::put('/letterhead/document/{document}', [App\Http\Controllers\Admin\LetterheadController::class, 'documentUpdate'])->name('letterhead.document.update');
    Route::delete('/letterhead/document/{document}', [App\Http\Controllers\Admin\LetterheadController::class, 'documentDestroy'])->name('letterhead.document.destroy');
    Route::get('/letterhead/document/{document}/preview', [App\Http\Controllers\Admin\LetterheadController::class, 'documentPreview'])->name('letterhead.document.preview');
    Route::get('/letterhead/document/{document}/download', [App\Http\Controllers\Admin\LetterheadController::class, 'documentDownload'])->name('letterhead.document.download');
});

// Organization Admin Routes
Route::middleware(['auth', 'role:org-admin|super-admin'])->prefix('organization')->name('organization.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Organization\OrganizationDashboardController::class, 'index'])->name('dashboard');

    // Tournament Management (Organization Admin)
    Route::get('/tournaments', [App\Http\Controllers\Admin\AdminTournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [App\Http\Controllers\Admin\AdminTournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [App\Http\Controllers\Admin\AdminTournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}', [App\Http\Controllers\Admin\AdminTournamentController::class, 'show'])->name('tournaments.show');
    Route::get('/tournaments/{tournament}/edit', [App\Http\Controllers\Admin\AdminTournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [App\Http\Controllers\Admin\AdminTournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [App\Http\Controllers\Admin\AdminTournamentController::class, 'destroy'])->name('tournaments.destroy');

    // Tournament Actions
    Route::post('/tournaments/{tournament}/open-registration', [App\Http\Controllers\Admin\AdminTournamentController::class, 'openRegistration'])->name('tournaments.open-registration');
    Route::post('/tournaments/{tournament}/close-registration', [App\Http\Controllers\Admin\AdminTournamentController::class, 'closeRegistration'])->name('tournaments.close-registration');
    Route::post('/tournaments/{tournament}/start', [App\Http\Controllers\Admin\AdminTournamentController::class, 'startTournament'])->name('tournaments.start');
    Route::post('/tournaments/{tournament}/complete', [App\Http\Controllers\Admin\AdminTournamentController::class, 'completeTournament'])->name('tournaments.complete');
    Route::post('/tournaments/{tournament}/generate-fixtures', [App\Http\Controllers\Admin\AdminTournamentController::class, 'generateFixtures'])->name('tournaments.generate-fixtures');
    Route::post('/tournaments/{tournament}/recalculate-standings', [App\Http\Controllers\Admin\AdminTournamentController::class, 'recalculateStandings'])->name('tournaments.recalculate-standings');
    Route::post('/tournaments/{tournament}/unlock-squads', [App\Http\Controllers\Admin\AdminTournamentController::class, 'unlockSquads'])->name('tournaments.unlock-squads');

    // Tournament Standings
    Route::get('/tournaments/{tournament}/standings', [App\Http\Controllers\Admin\AdminTournamentController::class, 'standings'])->name('tournaments.standings.index');

    // Tournament Teams Management
    Route::get('/tournaments/{tournament}/teams', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'index'])->name('tournaments.teams.index');
    Route::get('/tournaments/{tournament}/teams/create', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'create'])->name('tournaments.teams.create');
    Route::post('/tournaments/{tournament}/teams', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'store'])->name('tournaments.teams.store');
    Route::get('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'show'])->name('tournaments.teams.show');
    Route::get('/tournaments/{tournament}/teams/{team}/edit', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'edit'])->name('tournaments.teams.edit');
    Route::put('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'update'])->name('tournaments.teams.update');
    Route::delete('/tournaments/{tournament}/teams/{team}', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'destroy'])->name('tournaments.teams.destroy');
    Route::post('/tournaments/{tournament}/teams/{team}/approve', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'approve'])->name('tournaments.teams.approve');
    Route::post('/tournaments/{tournament}/teams/{team}/reject', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'reject'])->name('tournaments.teams.reject');
    Route::post('/tournaments/{tournament}/teams/{team}/request-correction', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'requestCorrection'])->name('tournaments.teams.request-correction');
    Route::post('/tournaments/{tournament}/teams/bulk-upload', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'bulkUploadTeams'])->name('tournaments.teams.bulk-upload');
    Route::post('/tournaments/{tournament}/teams/bulk-add', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'bulkAddTeams'])->name('tournaments.teams.bulk-add');
    Route::post('/tournaments/{tournament}/teams/check-existing', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'checkExistingTeams'])->name('tournaments.teams.check-existing');
    Route::get('/tournaments/teams/template', [App\Http\Controllers\Admin\AdminTournamentTeamController::class, 'downloadTeamTemplate'])->name('tournaments.teams.template');

    // Tournament Squads
    Route::get('/tournaments/{tournament}/teams/{team}/squads', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'index'])->name('tournaments.squads.index');
    Route::get('/tournaments/{tournament}/teams/{team}/squads/create', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'create'])->name('tournaments.squads.create');
    Route::post('/tournaments/{tournament}/teams/{team}/squads', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'store'])->name('tournaments.squads.store');
    Route::put('/tournaments/{tournament}/teams/{team}/squads/{squad}', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'update'])->name('tournaments.squads.update');
    Route::delete('/tournaments/{tournament}/teams/{team}/squads/{squad}', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'destroy'])->name('tournaments.squads.destroy');
    Route::post('/tournaments/{tournament}/teams/{team}/squads/{squad}/verify', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'verify'])->name('tournaments.squads.verify');
    Route::post('/tournaments/{tournament}/teams/{team}/squads/{squad}/reject', [App\Http\Controllers\Admin\AdminTournamentSquadController::class, 'reject'])->name('tournaments.squads.reject');

    // Tournament Matches
    Route::get('/tournaments/{tournament}/matches', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'index'])->name('tournaments.matches.index');
    Route::get('/tournaments/{tournament}/matches/create', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'create'])->name('tournaments.matches.create');
    Route::post('/tournaments/{tournament}/matches', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'store'])->name('tournaments.matches.store');
    Route::get('/tournaments/{tournament}/matches/{match}', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'show'])->name('tournaments.matches.show');
    Route::get('/tournaments/{tournament}/matches/{match}/edit', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'edit'])->name('tournaments.matches.edit');
    Route::put('/tournaments/{tournament}/matches/{match}', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'update'])->name('tournaments.matches.update');
    Route::delete('/tournaments/{tournament}/matches/{match}', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'destroy'])->name('tournaments.matches.destroy');
    Route::post('/tournaments/{tournament}/matches/{match}/record-result', [App\Http\Controllers\Admin\AdminTournamentMatchController::class, 'recordResult'])->name('tournaments.matches.record-result');

    // Tournament Pools
    Route::get('/tournaments/{tournament}/pools', [App\Http\Controllers\Admin\TournamentPoolController::class, 'index'])->name('tournaments.pools.index');
    Route::get('/tournaments/{tournament}/pools/create', [App\Http\Controllers\Admin\TournamentPoolController::class, 'create'])->name('tournaments.pools.create');
    Route::post('/tournaments/{tournament}/pools', [App\Http\Controllers\Admin\TournamentPoolController::class, 'store'])->name('tournaments.pools.store');

    // Attendance Management
    Route::get('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{attendance}', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/attendance/export', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'showExportPage'])->name('attendance.export.page');
    Route::get('/attendance/export/download', [App\Http\Controllers\Admin\AdminAttendanceController::class, 'export'])->name('attendance.export');

    // Organization Roles & Permissions Management
    Route::get('/roles', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'create'])->name('roles.create');
    Route::post('/roles', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'destroy'])->name('roles.destroy');
    Route::post('/roles/{role}/assign', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'assignRole'])->name('roles.assign');
    Route::get('/roles/{role}/users', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'getRoleUsers'])->name('roles.users');
    Route::get('/roles/{role}/assignable-users', [App\Http\Controllers\Organization\OrgRoleManagementController::class, 'getAssignableUsers'])->name('roles.assignable-users');
});
