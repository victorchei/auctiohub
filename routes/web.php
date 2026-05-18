<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\LotManageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/lots', [LotController::class, 'index'])->name('lots.index');
Route::get('/lots/{lot:slug}', [LotController::class, 'show'])->name('lots.show');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Lot CRUD (own lots)
    Route::get('/lots-manage/create', [LotManageController::class, 'create'])->name('lots.create');
    Route::post('/lots-manage', [LotManageController::class, 'store'])->name('lots.store');
    Route::get('/lots-manage/{lot:slug}/edit', [LotManageController::class, 'edit'])->name('lots.edit');
    Route::put('/lots-manage/{lot:slug}', [LotManageController::class, 'update'])->name('lots.update');
    Route::delete('/lots-manage/{lot:slug}', [LotManageController::class, 'destroy'])->name('lots.destroy');

    // Bids
    Route::post('/lots/{lot:slug}/bids', [BidController::class, 'store'])->name('bids.store');

    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/{lot:slug}/toggle', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');

    // Comments
    Route::post('/lots/{lot:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/lots', [\App\Http\Controllers\Admin\LotModerationController::class, 'index'])->name('lots.index');
    Route::post('/lots/{lot}/cancel', [\App\Http\Controllers\Admin\LotModerationController::class, 'cancel'])->name('lots.cancel');
    Route::delete('/lots/{lot}', [\App\Http\Controllers\Admin\LotModerationController::class, 'destroy'])->name('lots.destroy');
    Route::post('/lots/{id}/restore', [\App\Http\Controllers\Admin\LotModerationController::class, 'restore'])->name('lots.restore');
    Route::post('/lots/bulk-delete', [\App\Http\Controllers\Admin\LotModerationController::class, 'bulkDelete'])->name('lots.bulkDelete');
    Route::get('/lots/export', [\App\Http\Controllers\Admin\LotModerationController::class, 'exportCsv'])->name('lots.export');

    Route::get('/users', [\App\Http\Controllers\Admin\UserAdminController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserAdminController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\UserAdminController::class, 'unban'])->name('users.unban');
    Route::post('/users/{user}/promote', [\App\Http\Controllers\Admin\UserAdminController::class, 'promote'])->name('users.promote');

    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'index'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'destroy'])->name('categories.destroy');

    Route::get('/audit-log', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');
});

require __DIR__.'/auth.php';
