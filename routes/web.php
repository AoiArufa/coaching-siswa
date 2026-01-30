<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardParentController;
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
| Dashboard Global
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/dashboard', function () {
            return view('dashboards.admin', [
                'totalUsers'     => \App\Models\User::count(),
                'totalCoachings' => \App\Models\Coaching::count(),
                'totalJournals'  => \App\Models\Journal::count(),
            ]);
        })->name('admin.dashboard');

        Route::get('/admin/activity-log', [ActivityLogController::class, 'index'])
            ->name('admin.activity-log');
    });

    /*
    |--------------------------------------------------------------------------
    | GURU
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:guru')->group(function () {

        Route::get('/guru/dashboard', [CoachingController::class, 'index'])
            ->name('guru.dashboard');

        Route::resource('coachings', CoachingController::class);

        // 🔑 JURNAL (NESTED, FULL ACCESS)
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
    Route::middleware('role:murid')->group(function () {

        Route::get('/murid/dashboard', function () {
            return view('dashboards.murid', [
                'totalCoachings' => \App\Models\Coaching::where('murid_id', auth()->id())->count(),
                'totalJournals'  => \App\Models\Journal::whereHas(
                    'coaching',
                    fn($q) =>
                    $q->where('murid_id', auth()->id())
                )->count(),
            ]);
        })->name('murid.dashboard');

        Route::get('/murid/coachings', [CoachingController::class, 'forMurid'])
            ->name('murid.coachings');

        Route::get('/murid/coachings/{coaching}', [CoachingController::class, 'showForMurid'])
            ->name('murid.coachings.show');

        // ✅ hanya STORE jurnal
        Route::post(
            '/murid/coachings/{coaching}/journals',
            [JournalController::class, 'store']
        )->name('murid.journals.store');
    });

    /*
    |--------------------------------------------------------------------------
    | ORANG TUA
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:orang_tua')->group(function () {

        Route::get('/ortu/dashboard', function () {
            $childrenIds = auth()->user()->children->pluck('id');

            return view('dashboards.ortu', [
                'totalJournals' => \App\Models\Journal::whereHas(
                    'coaching.murid',
                    fn($q) =>
                    $q->whereIn('id', $childrenIds)
                )->count(),
            ]);
        })->name('ortu.dashboard');

        Route::get('/ortu/journals', [JournalController::class, 'forParent'])
            ->name('ortu.journals.index');
    });

    /*
    |--------------------------------------------------------------------------
    | PDF EXPORT (SHARED)
    |--------------------------------------------------------------------------
    */
    Route::get(
        '/coachings/{coaching}/journals/pdf',
        [JournalController::class, 'exportPdf']
    )->name('coachings.journals.pdf');

    /*
    |-------------------------------------------------------------------------
    | CHARTS
    |-------------------------------------------------------------------------
    */
    Route::get(
        '/coachings/{coaching}/journals/chart',
        [JournalController::class, 'chart']
    )->name('coachings.journals.chart');

    /*
    |--------------------------------------------------------------------------
    | Redirect Berdasarkan Role
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
