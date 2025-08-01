<?php

use App\Models\User;
use App\Models\Expense;
use App\Models\Category;
use App\Enums\CategoryType;
use Carbon\Carbon;
use Illuminate\Support\Str;

describe('ExpenseFactory', function () {
  it('creates an expense with valid data', function () {
    $expense = Expense::factory()->make();

    expect($expense->description)->toBeString()
      ->and($expense->amount)->toBeFloat()
      ->and($expense->amount)->toBeGreaterThan(0)
      ->and($expense->date)->toBeInstanceOf(Carbon::class)
      ->and($expense->user_id)->toBeInt()
      ->and($expense->category_id)->toBeInt()
      ->and($expense->transaction_group_uuid)->toBeNull()
      ->and($expense->installment_number)->toBeNull()
      ->and($expense->total_installments)->toBeNull();
  });

  it('creates expense with amount in valid range', function () {
    $expense = Expense::factory()->make();

    expect($expense->amount)->toBeGreaterThanOrEqual(10)
      ->and($expense->amount)->toBeLessThanOrEqual(2000);
  });

  it('creates expense with date in valid range', function () {
    $expense = Expense::factory()->make();
    $sixMonthsAgo = Carbon::now()->subMonths(6);
    $threeMonthsAhead = Carbon::now()->addMonths(3);

    expect($expense->date)->toBeGreaterThanOrEqual($sixMonthsAgo)
      ->and($expense->date)->toBeLessThanOrEqual($threeMonthsAhead);
  });

  it('creates expense for specific month', function () {
    $year = 2025;
    $month = 8;
    $expense = Expense::factory()->forMonth($year, $month)->make();

    expect($expense->date->year)->toBe($year)
      ->and($expense->date->month)->toBe($month);
  });

  it('creates installment expense with proper characteristics', function () {
    $totalInstallments = 6;
    $expense = Expense::factory()->installment($totalInstallments)->make();

    expect($expense->transaction_group_uuid)->not->toBeNull()
      ->and(Str::isUuid($expense->transaction_group_uuid))->toBeTrue()
      ->and($expense->installment_number)->toBe(1)
      ->and($expense->total_installments)->toBe($totalInstallments)
      ->and($expense->amount)->toBeGreaterThanOrEqual(100)
      ->and($expense->amount)->toBeLessThanOrEqual(1500);
  });

  it('creates installment expense with random installments when not specified', function () {
    $expense = Expense::factory()->installment()->make();

    expect($expense->total_installments)->toBeGreaterThanOrEqual(2)
      ->and($expense->total_installments)->toBeLessThanOrEqual(12);
  });

  it('creates recurring expense with proper characteristics', function () {
    $expense = Expense::factory()->recurring()->make();
    $recurringExpenses = [
      'Internet - Monthly fee',
      'Gym - Monthly fee',
      'Health insurance',
      'Car insurance',
      'Netflix subscription',
      'Spotify Premium',
      'Electricity bill',
      'Water bill',
      'Rent',
      'HOA fee'
    ];

    expect($recurringExpenses)->toContain($expense->description)
      ->and($expense->amount)->toBeGreaterThanOrEqual(50)
      ->and($expense->amount)->toBeLessThanOrEqual(800);
  });

  it('creates food expense with proper characteristics', function () {
    $expense = Expense::factory()->food()->make();
    $foodExpenses = [
      'Grocery shopping - Weekly',
      'Restaurant - Lunch',
      'Pizza delivery',
      'Bakery',
      'Breakfast',
      'Snack bar',
      'Farmers market',
      'Butcher shop',
      'Japanese delivery',
      'Ice cream'
    ];

    expect($foodExpenses)->toContain($expense->description)
      ->and($expense->amount)->toBeGreaterThanOrEqual(15)
      ->and($expense->amount)->toBeLessThanOrEqual(200);
  });

  it('creates transport expense with proper characteristics', function () {
    $expense = Expense::factory()->transport()->make();
    $transportExpenses = [
      'Fuel',
      'Uber/Taxi',
      'Public transport',
      'Parking',
      'Toll',
      'Car maintenance',
      'Car wash'
    ];

    expect($transportExpenses)->toContain($expense->description)
      ->and($expense->amount)->toBeGreaterThanOrEqual(10)
      ->and($expense->amount)->toBeLessThanOrEqual(300);
  });

  it('creates expense with proper relationships', function () {
    $user = User::factory()->create();
    $category = Category::factory()->expense()->create(['user_id' => $user->id]);

    $expense = Expense::factory()->create([
      'user_id' => $user->id,
      'category_id' => $category->id
    ]);

    expect($expense->user->id)->toBe($user->id)
      ->and($expense->category->id)->toBe($category->id)
      ->and($expense->category->type)->toBe(CategoryType::Expense);
  });

  it('generates realistic expense descriptions', function () {
    $validDescriptions = [
      'Grocery shopping - Weekly',
      'Fuel',
      'Restaurant lunch',
      'Gym - Monthly fee',
      'Internet - Monthly fee',
      'Electricity bill',
      'Water bill',
      'Medications',
      'Doctor appointment',
      'Movies',
      'Public transport',
      'Uber/Taxi',
      'Online shopping',
      'Bookstore',
      'Breakfast',
      'Car maintenance',
      'Clothes',
      'Cleaning supplies',
      'Birthday gift',
      'Food delivery',
      'Streaming subscription',
      'Online course',
      'Office supplies',
      'Car insurance',
      'Health insurance'
    ];

    $expense = Expense::factory()->make();

    expect($validDescriptions)->toContain($expense->description);
  });

  it('creates multiple expenses with varying data', function () {
    $expenses = Expense::factory(20)->create();

    $descriptions = $expenses->pluck('description')->unique();
    $amounts = $expenses->pluck('amount');

    expect($descriptions->count())->toBeGreaterThan(1)
      ->and($amounts->min())->toBeGreaterThanOrEqual(10)
      ->and($amounts->max())->toBeLessThanOrEqual(2000);
  });
});
