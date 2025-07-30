<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    // Rotas do Painel
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Rotas de Configurações
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Rotas Entradas (Incomes)
    Route::resource('incomes', IncomeController::class);

    // Rotas Saídas (Expenses)
    Route::resource('expenses', ExpenseController::class);

    // Rotas Categorias
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/auth.php';
