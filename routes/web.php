<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\CompanyComponent;
use App\Livewire\ClientComponent;
use App\Livewire\InvoiceComponent;
use App\Livewire\InvoiceSpecificationComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/company', CompanyComponent::class)->name('company');
    Route::get('/clients', ClientComponent::class)->name('clients');
    Route::get('/invoices', InvoiceComponent::class)->name('invoices.index');
    Route::get('/invoices/{invoice}/specifications', InvoiceSpecificationComponent::class)->name('invoices.specifications');
    Route::get('/invoices/{invoice}/preview', [InvoiceComponent::class, 'previewPdf'])->name('invoices.preview');
    Route::get('/invoices/{invoice}/download', [InvoiceComponent::class, 'downloadPdf'])->name('invoices.download');
    Route::get('/invoices/{invoice}/specifications/preview', [InvoiceSpecificationComponent::class, 'previewPdf'])->name('invoices.specifications.preview');
});

require __DIR__.'/auth.php';
