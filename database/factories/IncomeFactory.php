<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Income;
use App\Models\User;
use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Income>
 */
class IncomeFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    // Various income descriptions
    $descriptions = [
      'Monthly salary',
      'Freelance - Web project',
      'Technical consulting',
      'Product sales',
      'Investment dividends',
      'Property rental',
      'Annual bonus',
      'Overtime work',
      'Cashback',
      'Money gift',
      'Refund',
      'Sales commission',
      'Investment returns',
      'Online monetization',
      'Service provided'
    ];

    return [
      'description' => fake()->randomElement($descriptions),
      'amount' => fake()->randomFloat(2, 100, 15000), // Between $100 and $15,000
      'date' => fake()->dateTimeBetween('-6 months', '+3 months'),
      'user_id' => User::factory(),
      'category_id' => Category::factory()->income(),
    ];
  }

  /**
   * Create an income for a specific month and year.
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
   * Create a salary income.
   */
  public function salary(): static
  {
    return $this->state(fn(array $attributes) => [
      'description' => 'Monthly salary',
      'amount' => fake()->randomFloat(2, 3000, 12000),
    ]);
  }

  /**
   * Create a freelance income.
   */
  public function freelance(): static
  {
    $projects = [
      'Corporate website',
      'Mobile app',
      'Management system',
      'E-commerce',
      'Landing page',
      'Technical consulting',
      'System maintenance'
    ];

    return $this->state(fn(array $attributes) => [
      'description' => 'Freelance - ' . fake()->randomElement($projects),
      'amount' => fake()->randomFloat(2, 500, 8000),
    ]);
  }
}
