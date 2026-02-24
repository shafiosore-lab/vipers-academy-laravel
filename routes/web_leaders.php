<?php

// Leaders Management Routes (Meet Our Leaders page)
// Add these routes to routes/web.php

Route::get('/leaders', [App\Http\Controllers\Admin\AdminLeaderController::class, 'index'])->name('leaders.index');
Route::get('/leaders/create', [App\Http\Controllers\Admin\AdminLeaderController::class, 'create'])->name('leaders.create');
Route::post('/leaders', [App\Http\Controllers\Admin\AdminLeaderController::class, 'store'])->name('leaders.store');
Route::get('/leaders/{leader}', [App\Http\Controllers\Admin\AdminLeaderController::class, 'show'])->name('leaders.show');
Route::get('/leaders/{leader}/edit', [App\Http\Controllers\Admin\AdminLeaderController::class, 'edit'])->name('leaders.edit');
Route::put('/leaders/{leader}', [App\Http\Controllers\Admin\AdminLeaderController::class, 'update'])->name('leaders.update');
Route::delete('/leaders/{leader}', [App\Http\Controllers\Admin\AdminLeaderController::class, 'destroy'])->name('leaders.destroy');
Route::post('/leaders/{leader}/toggle-status', [App\Http\Controllers\Admin\AdminLeaderController::class, 'toggleStatus'])->name('leaders.toggle-status');
Route::post('/leaders/reorder', [App\Http\Controllers\Admin\AdminLeaderController::class, 'reorder'])->name('leaders.reorder');
