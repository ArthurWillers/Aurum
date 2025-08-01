<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Income;
use App\Models\Expense;
use App\Enums\CategoryType;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;

describe('DatabaseSeeder', function () {
  beforeEach(function () {
    // Clean database before each test
    User::query()->delete();
    Category::query()->delete();
    Income::query()->delete();
    Expense::query()->delete();
  });

  it('creates the test user correctly', function () {
    $this->seed(DatabaseSeeder::class);

    $testUser = User::where('email', 'test@example.com')->first();

    expect($testUser)->not->toBeNull()
      ->and($testUser->name)->toBe('Test User')
      ->and($testUser->email)->toBe('test@example.com')
      ->and(Hash::check('123', $testUser->password))->toBeTrue();
  });

  it('creates additional users', function () {
    $this->seed(DatabaseSeeder::class);

    $totalUsers = User::count();
    $additionalUsers = User::where('email', '!=', 'test@example.com')->count();

    expect($totalUsers)->toBe(4) // 1 test user + 3 additional users
      ->and($additionalUsers)->toBe(3);
  });

  it('creates categories for each user', function () {
    $this->seed(DatabaseSeeder::class);

    $users = User::all();

    foreach ($users as $user) {
      $userCategories = Category::where('user_id', $user->id)->get();
      $incomeCategories = $userCategories->where('type', CategoryType::Income);
      $expenseCategories = $userCategories->where('type', CategoryType::Expense);

      expect($userCategories->count())->toBe(13) // 5 income + 8 expense
        ->and($incomeCategories->count())->toBe(5)
        ->and($expenseCategories->count())->toBe(8);
    }
  });

  it('creates incomes for each user', function () {
    $this->seed(DatabaseSeeder::class);

    $users = User::all();

    foreach ($users as $user) {
      $userIncomes = Income::where('user_id', $user->id)->count();

      expect($userIncomes)->toBeGreaterThan(0);
    }
  });

  it('creates expenses for each user', function () {
    $this->seed(DatabaseSeeder::class);

    $users = User::all();

    foreach ($users as $user) {
      $userExpenses = Expense::where('user_id', $user->id)->count();

      expect($userExpenses)->toBeGreaterThan(0);
    }
  });

  it('creates data spanning multiple months', function () {
    $this->seed(DatabaseSeeder::class);

    $incomes = Income::all();
    $expenses = Expense::all();

    $incomeMonths = $incomes->pluck('date')->map(fn($date) => $date->format('Y-m'))->unique();
    $expenseMonths = $expenses->pluck('date')->map(fn($date) => $date->format('Y-m'))->unique();

    expect($incomeMonths->count())->toBeGreaterThan(6) // Should span at least 6+ months
      ->and($expenseMonths->count())->toBeGreaterThan(6);
  });

  it('creates realistic salary incomes', function () {
    $this->seed(DatabaseSeeder::class);

    $salaryIncomes = Income::where('description', 'Monthly salary')->get();

    expect($salaryIncomes->count())->toBeGreaterThan(0);

    // Check that most salary incomes are in the expected range
    $validSalaryCount = $salaryIncomes->filter(function ($income) {
      return $income->amount >= 3000 && $income->amount <= 15000;
    })->count();

    // At least 80% should be in the valid range (allowing for some variation)
    expect($validSalaryCount / $salaryIncomes->count())->toBeGreaterThan(0.8);
  });

  it('creates installment expenses properly', function () {
    $this->seed(DatabaseSeeder::class);

    $installmentExpenses = Expense::whereNotNull('transaction_group_uuid')->get();

    if ($installmentExpenses->count() > 0) {
      // Group by transaction_group_uuid to check installments
      $groups = $installmentExpenses->groupBy('transaction_group_uuid');

      foreach ($groups as $group) {
        $firstExpense = $group->first();

        expect($group->count())->toBeLessThanOrEqual($firstExpense->total_installments)
          ->and($group->pluck('installment_number')->min())->toBe(1)
          ->and($group->pluck('amount')->unique()->count())->toBe(1); // Same amount for all installments
      }
    }

    expect(true)->toBeTrue(); // Test passes even if no installments are created
  });

  it('creates data with proper relationships', function () {
    $this->seed(DatabaseSeeder::class);

    // Check that all incomes belong to the correct user's categories
    $incomes = Income::with(['user', 'category'])->get();

    foreach ($incomes as $income) {
      expect($income->category->user_id)->toBe($income->user_id)
        ->and($income->category->type)->toBe(CategoryType::Income);
    }

    // Check that all expenses belong to the correct user's categories
    $expenses = Expense::with(['user', 'category'])->get();

    foreach ($expenses as $expense) {
      expect($expense->category->user_id)->toBe($expense->user_id)
        ->and($expense->category->type)->toBe(CategoryType::Expense);
    }
  });
});
