<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;
use App\Livewire\InfaqPage;
use App\Livewire\TermsPage;
use App\Livewire\RegisterForm;
use App\Livewire\UserRegistration;
use App\Http\Controllers\InfaqController;


Route::get('/', HomePage::class)->name('home');  // Correct way to use Livewire components in routes

Route::get('/infaq', InfaqPage::class)->name('infaq');

Route::post('/infaq/store', [InfaqController::class, 'store'])->name('infaq.store');
Route::get('/infaq/callback', [InfaqController::class, 'handlePaymentCallback'])->name('infaq.callback');

Route::get('/register', UserRegistration::class)->name('register');





Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

// Add the new route for "Maklumat Ahli" page
Route::get('/maklumat-ahli', function () {
    return view('UserProfile');
})->name('maklumat-ahli');
});
