<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing
|--------------------------------------------------------------------------
*/

Route::view('/', 'landing')->name('landing');

/*
|--------------------------------------------------------------------------
| Dashboard (Redirect Global)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route('redirect');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('dashboards.admin', [
                    'totalUsers'     => \App\Models\User::count(),
                    'totalCoachings' => \App\Models\Coaching::count(),
                    'totalJournals'  => \App\Models\Journal::count(),
                ]);
            })->name('dashboard');

            Route::get('/activity-log', [ActivityLogController::class, 'index'])
                ->name('activity-log');
        });

    /*
    |--------------------------------------------------------------------------
    | GURU
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:guru')
        ->prefix('guru')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [CoachingController::class, 'index'])
                ->name('dashboard');
            Route::get(
                '/guru/dashboard',
                [App\Http\Controllers\CoachingController::class, 'index']
            )->name('guru.dashboard');

            // Analytics
            Route::get('/analytics', [CoachingController::class, 'analytics'])
                ->name('analytics');

            // Resource Coaching
            Route::resource('coachings', CoachingController::class);

            // Nested Journals (Full Access)
            Route::resource(
                'coachings.journals',
                JournalController::class
            )->except('show');
        });

    /*
    |--------------------------------------------------------------------------
    | MURID
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:murid')
        ->prefix('murid')
        ->name('murid.')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('dashboards.murid', [
                    'totalCoachings' => \App\Models\Coaching::where('murid_id', auth()->id())->count(),
                    'totalJournals'  => \App\Models\Journal::whereHas(
                        'coaching',
                        fn($q) => $q->where('murid_id', auth()->id())
                    )->count(),
                ]);
            })->name('dashboard');

            Route::get('/coachings', [CoachingController::class, 'forMurid'])
                ->name('coachings.index');

            Route::get('/coachings/{coaching}', [CoachingController::class, 'showForMurid'])
                ->name('coachings.show');

            // Murid hanya bisa STORE jurnal
            Route::post(
                '/coachings/{coaching}/journals',
                [JournalController::class, 'store']
            )->name('journals.store');
        });

    /*
    |--------------------------------------------------------------------------
    | ORANG TUA
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:orang_tua')
        ->prefix('ortu')
        ->name('ortu.')
        ->group(function () {

            Route::get('/dashboard', function () {
                $childrenIds = auth()->user()->children->pluck('id');

                return view('dashboards.ortu', [
                    'totalJournals' => \App\Models\Journal::whereHas(
                        'coaching.murid',
                        fn($q) => $q->whereIn('id', $childrenIds)
                    )->count(),
                ]);
            })->name('dashboard');

            Route::get('/journals', [JournalController::class, 'forParent'])
                ->name('journals.index');
        });

    /*
    |--------------------------------------------------------------------------
    | SHARED FEATURES (AUTH ONLY)
    |--------------------------------------------------------------------------
    */

    // Export PDF
    Route::get(
        '/coachings/{coaching}/journals/pdf',
        [JournalController::class, 'exportPdf']
    )->name('coachings.journals.pdf');

    // Chart
    Route::get(
        '/coachings/{coaching}/journals/chart',
        [JournalController::class, 'chart']
    )->name('coachings.journals.chart');

    /*
    |--------------------------------------------------------------------------
    | Redirect By Role
    |--------------------------------------------------------------------------
    */

    Route::get('/redirect', function () {
        return match (auth()->user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'guru'      => redirect()->route('guru.dashboard'),
            'murid'     => redirect()->route('murid.dashboard'),
            'orang_tua' => redirect()->route('ortu.dashboard'),
            default     => abort(403),
        };
    })->name('redirect');
});

require __DIR__ . '/auth.php';
