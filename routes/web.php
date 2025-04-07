<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\CompanyComponent;
use App\Livewire\ClientComponent;
use App\Livewire\InvoiceComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', \App\Livewire\Pages\Profile\Edit::class)->name('profile.edit');

    Route::get('/company', CompanyComponent::class)->name('company');
    Route::get('/clients', ClientComponent::class)->name('clients');
    Route::get('/invoices', InvoiceComponent::class)->name('invoices');
});

require __DIR__.'/auth.php';
