<?php

use App\Models\User;
use App\Models\Category;
use App\Enums\CategoryType;

describe('CategoryFactory', function () {
  it('creates a category with valid data', function () {
    $category = Category::factory()->make();

    expect($category->name)->toBeString()
      ->and($category->type)->toBeInstanceOf(CategoryType::class)
      ->and($category->user_id)->toBeInt();
  });

  it('creates an income category', function () {
    $category = Category::factory()->income()->make();

    expect($category->type)->toBe(CategoryType::Income)
      ->and($category->name)->toBeString()
      ->and($category->isIncome())->toBeTrue();
  });

  it('creates an expense category', function () {
    $category = Category::factory()->expense()->make();

    expect($category->type)->toBe(CategoryType::Expense)
      ->and($category->name)->toBeString()
      ->and($category->isIncome())->toBeFalse();
  });

  it('creates categories with proper relationships', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    expect($category->user->id)->toBe($user->id)
      ->and($category->user)->toBeInstanceOf(User::class);
  });

  it('creates multiple categories with different types', function () {
    $categories = Category::factory(10)->create();

    $incomeCount = $categories->where('type', CategoryType::Income)->count();
    $expenseCount = $categories->where('type', CategoryType::Expense)->count();

    expect($incomeCount + $expenseCount)->toBe(10)
      ->and($incomeCount)->toBeGreaterThan(0)
      ->and($expenseCount)->toBeGreaterThan(0);
  });

  it('generates realistic category names for income', function () {
    $incomeCategories = ['Salary', 'Freelance', 'Investments', 'Rent', 'Sales', 'Bonus', 'Dividends', 'Consulting', 'Side Income', 'Gift'];

    $category = Category::factory()->income()->make();

    expect($incomeCategories)->toContain($category->name);
  });

  it('generates realistic category names for expense', function () {
    $expenseCategories = ['Food', 'Transportation', 'Housing', 'Healthcare', 'Education', 'Entertainment', 'Clothing', 'Technology', 'Groceries', 'Pharmacy', 'Restaurant', 'Fuel', 'Gym', 'Movies', 'Travel'];

    $category = Category::factory()->expense()->make();

    expect($expenseCategories)->toContain($category->name);
  });
});
