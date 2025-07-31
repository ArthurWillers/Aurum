<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Categories\Index as CategoriesIndex;
use App\Livewire\Categories\Create as CategoriesCreate;
use App\Livewire\Categories\Edit as CategoriesEdit;
use App\Livewire\Incomes\Index as IncomesIndex;
use App\Livewire\Incomes\Create as IncomesCreate;
use App\Livewire\Incomes\Edit as IncomesEdit;
use App\Livewire\Expenses\Index as ExpensesIndex;
use App\Livewire\Expenses\Create as ExpensesCreate;
use App\Livewire\Expenses\Edit as ExpensesEdit;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    // Rotas do Painel
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Rotas de Configurações
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Rotas de Categorias
    Route::get('categories', CategoriesIndex::class)->name('categories.index');
    Route::get('categories/create', CategoriesCreate::class)->name('categories.create');
    Route::get('categories/{category}/edit', CategoriesEdit::class)->name('categories.edit');

    // Rotas de Receitas
    Route::get('/incomes', IncomesIndex::class)->name('incomes.index');
    Route::get('/incomes/create', IncomesCreate::class)->name('incomes.create');
    Route::get('/incomes/{income}/edit', IncomesEdit::class)->name('incomes.edit');

    // Rotas de Despesas
    Route::get('/expenses', ExpensesIndex::class)->name('expenses.index');
    Route::get('/expenses/create', ExpensesCreate::class)->name('expenses.create');
    Route::get('/expenses/{expense}/edit', ExpensesEdit::class)->name('expenses.edit');

});

require __DIR__.'/auth.php';
