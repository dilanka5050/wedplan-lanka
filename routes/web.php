<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestRsvpController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\GuestImportController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\DigitalInvitationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\SeatingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

// ── ADMIN PANEL ──────────────────────────────────────────────────────────
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',           [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',      [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}',      [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/upgrade',        [AdminController::class, 'upgradePlan'])->name('users.upgrade');
    Route::get('/weddings',          [AdminController::class, 'weddings'])->name('weddings');
    Route::get('/weddings/{wedding}/edit',    [AdminController::class, 'editWedding'])->name('weddings.edit');
    Route::put('/weddings/{wedding}',         [AdminController::class, 'updateWedding'])->name('weddings.update');
    Route::post('/weddings/{wedding}/archive',[AdminController::class, 'archiveVault'])->name('weddings.archive');
    Route::post('/weddings/{wedding}/storage',[AdminController::class, 'setStorageLimit'])->name('weddings.storage');
    Route::get('/settings',                  [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings/debug',           [AdminController::class, 'toggleDebug'])->name('settings.debug');
    Route::post('/settings/template',        [AdminController::class, 'toggleTemplate'])->name('settings.template');
    Route::post('/settings/price',           [AdminController::class, 'updatePrice'])->name('settings.price');
});

// Impersonation (admin accessing customer wedding)
Route::middleware('auth')->group(function () {
    Route::get('/impersonate/stop', [ImpersonateController::class, 'stop'])->name('impersonate.stop');
    Route::get('/impersonate/{user}', [ImpersonateController::class, 'start'])->name('impersonate.start');

    // Admin-only: customize any wedding's digital invitation
    Route::get('/admin/weddings/{wedding}/digital-customize', [DigitalInvitationController::class, 'adminCustomize'])->name('admin.digital-invitations.customize');
    Route::post('/admin/weddings/{wedding}/digital-customize', [DigitalInvitationController::class, 'adminSaveCustomize'])->name('admin.digital-invitations.customize.save');
});

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public guest invitation links (no auth required)
Route::get('/i/{token}', [GuestRsvpController::class, 'show'])->name('invite.show');
Route::post('/i/{token}/rsvp', [GuestRsvpController::class, 'rsvp'])->name('invite.rsvp');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    return response()->view('robots')->header('Content-Type', 'text/plain');
})->name('robots');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Wedding Setup (no wedding required)
    Route::get('/wedding/setup', [WeddingController::class, 'setup'])->name('wedding.setup');
    Route::post('/wedding', [WeddingController::class, 'store'])->name('wedding.store');

    // Wedding Edit (requires wedding)
    Route::get('/wedding/edit', [WeddingController::class, 'edit'])->name('wedding.edit')->middleware('requires.wedding');
    Route::put('/wedding', [WeddingController::class, 'update'])->name('wedding.update')->middleware('requires.wedding');

    // All routes below require a wedding workspace
    Route::middleware('requires.wedding')->group(function () {

        // Notes (free feature)
        Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
        Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
        Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
        Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

        // Tasks
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

        // Budget
        Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
        Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store')->middleware('check.limit:budgets');
        Route::put('/budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
        Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

        // Vault (File Uploads — partially free: 5 free, unlimited pro)
        Route::get('/vault', [FileController::class, 'index'])->name('vault.index');
        Route::post('/vault', [FileController::class, 'store'])->name('vault.store')->middleware('check.limit:uploads');
        Route::delete('/vault/{file}', [FileController::class, 'destroy'])->name('vault.destroy');
        Route::get('/vault/{file}/download', [FileController::class, 'download'])->name('vault.download');
        Route::get('/vault/{file}/stream', [FileController::class, 'stream'])->name('vault.stream');

        // Guests
        Route::get('/guests', [GuestController::class, 'index'])->name('guests.index');
        Route::post('/guests', [GuestController::class, 'store'])->name('guests.store')
            ->middleware('check.limit:guests');
        Route::put('/guests/{guest}', [GuestController::class, 'update'])->name('guests.update');
        Route::delete('/guests/{guest}', [GuestController::class, 'destroy'])->name('guests.destroy');
        Route::get('/guests/export', [GuestController::class, 'export'])->name('guests.export');
        Route::get('/guests/print', [GuestController::class, 'print'])->name('guests.print');

        // Guest bulk import + export + print (Pro only)
        Route::middleware('check.feature:separate_guest_lists')->group(function () {
            Route::get('/guests/import/template', [GuestImportController::class, 'template'])->name('guests.import.template');
            Route::post('/guests/import/preview', [GuestImportController::class, 'preview'])->name('guests.import.preview');
            Route::post('/guests/import', [GuestImportController::class, 'import'])->name('guests.import');
            Route::get('/guests/export', [GuestController::class, 'export'])->name('guests.export');
            Route::get('/guests/print', [GuestController::class, 'print'])->name('guests.print');
        });
        Route::middleware('check.feature:timeline')->group(function () {
            Route::get('/timeline', [TimelineController::class, 'index'])->name('timelines.index');
            Route::post('/timeline', [TimelineController::class, 'store'])->name('timelines.store');
            Route::put('/timeline/{timeline}', [TimelineController::class, 'update'])->name('timelines.update');
            Route::delete('/timeline/{timeline}', [TimelineController::class, 'destroy'])->name('timelines.destroy');
        });

        // Invitations (Pro feature)
        Route::middleware('check.feature:invitations')->group(function () {
            Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
            Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
            Route::get('/invitations/preview', [InvitationController::class, 'preview'])->name('invitations.preview');
            Route::get('/invitations/guest/{guest}', [InvitationController::class, 'guestCard'])->name('invitations.guest');

            // Digital animated invitations
            Route::get('/digital-invitations', [DigitalInvitationController::class, 'index'])->name('digital-invitations.index');
            Route::get('/digital-invitations/preview/{template}', [DigitalInvitationController::class, 'preview'])->name('digital-invitations.preview');
            Route::get('/digital-invitations/guest/{guest}', [DigitalInvitationController::class, 'guestCard'])->name('digital-invitations.guest');
            Route::get('/digital-invitations/customize', [DigitalInvitationController::class, 'customize'])->name('digital-invitations.customize');
            Route::post('/digital-invitations/customize', [DigitalInvitationController::class, 'saveCustomize'])->name('digital-invitations.customize.save');
            Route::post('/digital-invitations/set-template', [DigitalInvitationController::class, 'setTemplate'])->name('digital-invitations.set-template');
            Route::post('/digital-invitations/gallery/upload', [DigitalInvitationController::class, 'galleryUpload'])->name('digital-invitations.gallery.upload');
            Route::delete('/digital-invitations/gallery/{file}', [DigitalInvitationController::class, 'galleryDelete'])->name('digital-invitations.gallery.delete');
            Route::get('/digital-invitations/gallery/{file}/stream', [DigitalInvitationController::class, 'galleryStream'])->name('digital-invitations.gallery.stream');
        });

        // Seating Plan (Pro feature)
        Route::middleware('check.feature:seating')->group(function () {
            Route::get('/seating', [SeatingController::class, 'index'])->name('seating.index');
            Route::post('/seating', [SeatingController::class, 'store'])->name('seating.store');
            Route::put('/seating/{seatingTable}', [SeatingController::class, 'update'])->name('seating.update');
            Route::delete('/seating/{seatingTable}', [SeatingController::class, 'destroy'])->name('seating.destroy');
            Route::post('/seating/{seatingTable}/assign', [SeatingController::class, 'assign'])->name('seating.assign');
            Route::delete('/seating/assignment/{seatingAssignment}', [SeatingController::class, 'unassign'])->name('seating.unassign');
        });

    }); // end requires.wedding
});

require __DIR__ . '/auth.php';
