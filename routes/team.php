<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Team\TeamDashboardController;

/*
|--------------------------------------------------------------------------
| Team Dashboard Routes
|--------------------------------------------------------------------------
|
| These routes are for team administrators to manage their teams and players
| in tournaments. Requires authentication.
|
*/

Route::middleware(['auth'])->group(function () {

    // Team Dashboard Home
    Route::get('/dashboard', [TeamDashboardController::class, 'index'])->name('team.dashboard');

    // Tournament Registration
    Route::get('/tournaments', [TeamDashboardController::class, 'tournaments'])->name('team.tournaments.index');
    Route::get('/tournaments/{tournament}/register', [TeamDashboardController::class, 'showRegister'])->name('team.tournament.register');
    Route::post('/tournaments/{tournament}/register', [TeamDashboardController::class, 'register'])->name('team.tournament.register.post');

    // Tournament Team Dashboard
    Route::get('/tournaments/{tournament}', [TeamDashboardController::class, 'tournamentShow'])->name('team.tournament.show');

    // Player Management
    Route::get('/tournaments/{tournament}/players', [TeamDashboardController::class, 'players'])->name('team.players');

    // Add new player
    Route::get('/tournaments/{tournament}/players/create', [TeamDashboardController::class, 'showAddPlayer'])->name('team.players.create');
    Route::post('/tournaments/{tournament}/players', [TeamDashboardController::class, 'storePlayer'])->name('team.players.store');

    // Edit player
    Route::get('/tournaments/{tournament}/players/{squad}/edit', [TeamDashboardController::class, 'showEditPlayer'])->name('team.players.edit');
    Route::put('/tournaments/{tournament}/players/{squad}', [TeamDashboardController::class, 'updatePlayer'])->name('team.players.update');

    // View player
    Route::get('/tournaments/{tournament}/players/{squad}', [TeamDashboardController::class, 'viewPlayer'])->name('team.players.view');

    // Delete player
    Route::delete('/tournaments/{tournament}/players/{squad}', [TeamDashboardController::class, 'destroyPlayer'])->name('team.players.destroy');

    // Bulk upload
    Route::get('/tournaments/{tournament}/players/bulk-upload', [TeamDashboardController::class, 'showBulkUpload'])->name('team.players.bulk-upload');
    Route::post('/tournaments/{tournament}/players/bulk-upload', [TeamDashboardController::class, 'bulkUpload'])->name('team.players.bulk-upload.post');

    // Download template
    Route::get('/players/template', [TeamDashboardController::class, 'downloadTemplate'])->name('team.players.template');

    // Export players
    Route::get('/tournaments/{tournament}/players/export', [TeamDashboardController::class, 'exportPlayers'])->name('team.players.export');
});
