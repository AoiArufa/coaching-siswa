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
Route::get('/', function () {
    return view('landing');
})->name('landing');

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
    | ROLE: ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        Route::get('/admin/dashboard', function () {
            return view('dashboards.admin');
        })->name('admin.dashboard');

        // ✅ C.3.3 Activity Log
        Route::get('/admin/activity-log', [ActivityLogController::class, 'index'])
            ->name('admin.activity-log');
    });

    /*
    |--------------------------------------------------------------------------
    | ROLE: GURU
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:guru')->group(function () {

        Route::get('/guru/dashboard', [CoachingController::class, 'index'])
            ->name('guru.dashboard');

        Route::resource('coachings', CoachingController::class);
        Route::resource('coachings.journals', JournalController::class)
            ->except('show');
    });

    /*
    |--------------------------------------------------------------------------
    | ROLE: MURID
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:murid')->group(function () {

        Route::get('/murid/dashboard', function () {
            return view('dashboards.murid');
        })->name('murid.dashboard');

        Route::get('/my-journals', [JournalController::class, 'myJournals'])
            ->name('journals.murid');

        Route::get('/murid/coachings', [CoachingController::class, 'forMurid'])
            ->name('murid.coachings');

        Route::get('/murid/coachings/{coaching}', [CoachingController::class, 'showForMurid'])
            ->name('murid.coachings.show');
    });

    /*
    |--------------------------------------------------------------------------
    | ROLE: ORANG TUA
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:orang_tua')->group(function () {

        Route::get('/ortu/dashboard', function () {
            return view('dashboards.ortu');
        })->name('ortu.dashboard');

        Route::get('/ortu/journals', [JournalController::class, 'forParent'])
            ->name('ortu.journals');
    });

    /*
    |--------------------------------------------------------------------------
    | Redirect Berdasarkan Role
    |--------------------------------------------------------------------------
    */
    Route::get('/redirect', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'murid' => redirect()->route('murid.dashboard'),
            'orang_tua' => redirect()->route('ortu.dashboard'),
            default => abort(403),
        };
    })->name('redirect');

});

require __DIR__.'/auth.php';
