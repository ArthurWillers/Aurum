<?php

namespace Database\Factories;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $type = fake()->randomElement(CategoryType::cases());

    // Common categories for expenses
    $expenseCategories = [
      'Food',
      'Transportation',
      'Housing',
      'Healthcare',
      'Education',
      'Entertainment',
      'Clothing',
      'Technology',
      'Groceries',
      'Pharmacy',
      'Restaurant',
      'Fuel',
      'Gym',
      'Movies',
      'Travel'
    ];

    // Common categories for income
    $incomeCategories = [
      'Salary',
      'Freelance',
      'Investments',
      'Rent',
      'Sales',
      'Bonus',
      'Dividends',
      'Consulting',
      'Side Income',
      'Gift'
    ];

    $name = $type === CategoryType::Expense
      ? fake()->randomElement($expenseCategories)
      : fake()->randomElement($incomeCategories);

    return [
      'name' => $name,
      'type' => $type,
      'user_id' => User::factory(),
    ];
  }

  /**
   * Indicate that the category is of type Income.
   */
  public function income(): static
  {
    $incomeCategories = [
      'Salary',
      'Freelance',
      'Investments',
      'Rent',
      'Sales',
      'Bonus',
      'Dividends',
      'Consulting',
      'Side Income',
      'Gift'
    ];

    return $this->state(fn(array $attributes) => [
      'type' => CategoryType::Income,
      'name' => fake()->randomElement($incomeCategories),
    ]);
  }

  /**
   * Indicate that the category is of type Expense.
   */
  public function expense(): static
  {
    $expenseCategories = [
      'Food',
      'Transportation',
      'Housing',
      'Healthcare',
      'Education',
      'Entertainment',
      'Clothing',
      'Technology',
      'Groceries',
      'Pharmacy',
      'Restaurant',
      'Fuel',
      'Gym',
      'Movies',
      'Travel'
    ];

    return $this->state(fn(array $attributes) => [
      'type' => CategoryType::Expense,
      'name' => fake()->randomElement($expenseCategories),
    ]);
  }
}
