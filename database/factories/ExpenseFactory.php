<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    // Various expense descriptions
    $descriptions = [
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

    return [
      'description' => fake()->randomElement($descriptions),
      'amount' => fake()->randomFloat(2, 10, 2000), // Between $10 and $2,000
      'date' => fake()->dateTimeBetween('-6 months', '+3 months'),
      'user_id' => User::factory(),
      'category_id' => Category::factory()->expense(),
      'transaction_group_uuid' => null,
      'installment_number' => null,
      'total_installments' => null,
    ];
  }

  /**
   * Create an expense for a specific month and year.
   */
  public function forMonth(int $year, int $month): static
  {
    return $this->state(fn(array $attributes) => [
      'date' => fake()->dateTimeBetween(
        "{$year}-{$month}-01",
        "{$year}-{$month}-" . date('t', mktime(0, 0, 0, $month, 1, $year))
      ),
    ]);
  }

  /**
   * Create an installment expense.
   */
  public function installment(?int $totalInstallments = null): static
  {
    $total = $totalInstallments ?? fake()->numberBetween(2, 12);
    $uuid = (string) Str::uuid();

    return $this->state(fn(array $attributes) => [
      'transaction_group_uuid' => $uuid,
      'installment_number' => 1,
      'total_installments' => $total,
      'amount' => fake()->randomFloat(2, 100, 1500),
    ]);
  }

  /**
   * Create a recurring monthly expense.
   */
  public function recurring(): static
  {
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

    return $this->state(fn(array $attributes) => [
      'description' => fake()->randomElement($recurringExpenses),
      'amount' => fake()->randomFloat(2, 50, 800),
    ]);
  }

  /**
   * Create a food-related expense.
   */
  public function food(): static
  {
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

    return $this->state(fn(array $attributes) => [
      'description' => fake()->randomElement($foodExpenses),
      'amount' => fake()->randomFloat(2, 15, 200),
    ]);
  }

  /**
   * Create a transport-related expense.
   */
  public function transport(): static
  {
    $transportExpenses = [
      'Fuel',
      'Uber/Taxi',
      'Public transport',
      'Parking',
      'Toll',
      'Car maintenance',
      'Car wash'
    ];

    return $this->state(fn(array $attributes) => [
      'description' => fake()->randomElement($transportExpenses),
      'amount' => fake()->randomFloat(2, 10, 300),
    ]);
  }
}
