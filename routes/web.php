<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\TkiDataGrid;
use App\Livewire\DocumentTracker;
use App\Livewire\TkiRegistrationForm;
use App\Livewire\UserProfile;
use App\Livewire\SystemSettings;
use Illuminate\Support\Facades\Auth;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');

    Route::get('/waiting-approval', function () {
        if (auth()->user()->account_status === 'approved') {
            return redirect('/');
        }
        return view('waiting-approval');
    })->name('waiting-approval');

    // Approved Users Only
    Route::middleware('approved')->group(function () {
        Route::get('/', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/tki-data', TkiDataGrid::class)->name('tki-data');
        Route::get('/document-tracker', DocumentTracker::class)->name('document-tracker');
        Route::get('/registration', TkiRegistrationForm::class)->name('registration');
        Route::get('/profile', UserProfile::class)->name('profile');
        
        Route::get('/tki/{tki}/cv', [\App\Http\Controllers\CvExportController::class, 'download'])->name('tki.cv');

        // Super Admin Only
        Route::middleware('role:Super Admin')->group(function () {
            Route::get('/settings', SystemSettings::class)->name('settings');
            Route::get('/history-log', \App\Livewire\SystemHistory::class)->name('history-log');
        });
    });
});
