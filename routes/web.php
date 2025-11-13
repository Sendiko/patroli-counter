<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\PatrolLogger;
use App\Livewire\FoundItemLogger; // Don't forget to import!
use App\Livewire\RoomManager;
use App\Livewire\AssistantManager;

Route::get('/assistants', AssistantManager::class)
    ->middleware(['auth'])
    ->name('assistants');

Route::get('/rooms', RoomManager::class)
    ->middleware(['auth'])
    ->name('rooms');

Route::get('/found-items', FoundItemLogger::class)
    ->middleware(['auth'])
    ->name('found-items');

Route::get('/patrol', PatrolLogger::class)
    ->middleware(['auth'])
    ->name('patrol');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');


    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
