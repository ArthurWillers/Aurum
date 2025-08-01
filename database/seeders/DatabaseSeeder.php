<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Income;
use App\Models\Expense;
use App\Enums\CategoryType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Test User (kept as is)
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('123'),
        ]);

        // Create additional users with Faker
        $additionalUsers = User::factory(3)->create();
        $allUsers = collect([$testUser])->merge($additionalUsers);

        // For each user, create categories, incomes and expenses
        $allUsers->each(function ($user) {
            $this->createDataForUser($user);
        });
    }

    /**
     * Create realistic data for a specific user
     */
    private function createDataForUser(User $user): void
    {
        // Create specific categories for the user
        $incomeCategories = Category::factory(5)->income()->create(['user_id' => $user->id]);
        $expenseCategories = Category::factory(8)->expense()->create(['user_id' => $user->id]);

        // Define data period (6 months ago to 3 months in the future)
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now()->addMonths(3);

        // Generate data for each month
        for ($date = $startDate->copy(); $date <= $endDate; $date->addMonth()) {
            $this->createMonthlyDataForUser($user, $incomeCategories, $expenseCategories, $date->year, $date->month);
        }
    }

    /**
     * Create monthly data for a user
     */
    private function createMonthlyDataForUser(User $user, $incomeCategories, $expenseCategories, int $year, int $month): void
    {
        // Monthly incomes
        // Salary (almost every month)
        if (fake()->boolean(90)) {
            Income::factory()
                ->salary()
                ->forMonth($year, $month)
                ->create([
                    'user_id' => $user->id,
                    'category_id' => $incomeCategories->where('name', 'Salary')->first()?->id ?? $incomeCategories->random()->id,
                    'date' => Carbon::create($year, $month, fake()->numberBetween(1, 5)),
                ]);
        }

        // Freelances (occasionally)
        if (fake()->boolean(30)) {
            Income::factory()
                ->freelance()
                ->forMonth($year, $month)
                ->create([
                    'user_id' => $user->id,
                    'category_id' => $incomeCategories->where('name', 'Freelance')->first()?->id ?? $incomeCategories->random()->id,
                ]);
        }

        // Other occasional incomes
        Income::factory(fake()->numberBetween(0, 3))
            ->forMonth($year, $month)
            ->create([
                'user_id' => $user->id,
                'category_id' => $incomeCategories->random()->id,
            ]);

        // Monthly expenses
        // Fixed expenses (every month)
        $fixedExpenses = [
            ['name' => 'Housing', 'amount' => fake()->randomFloat(2, 800, 2000), 'description' => 'Rent'],
            ['name' => 'Transportation', 'amount' => fake()->randomFloat(2, 200, 500), 'description' => 'Fuel'],
            ['name' => 'Food', 'amount' => fake()->randomFloat(2, 400, 800), 'description' => 'Grocery shopping - Monthly'],
        ];

        foreach ($fixedExpenses as $fixedExpense) {
            $category = $expenseCategories->where('name', $fixedExpense['name'])->first() ?? $expenseCategories->random();

            Expense::factory()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'amount' => $fixedExpense['amount'],
                'description' => $fixedExpense['description'],
                'date' => Carbon::create($year, $month, fake()->numberBetween(1, 28)),
            ]);
        }

        // Variable expenses (random quantity per month)
        Expense::factory(fake()->numberBetween(10, 25))
            ->forMonth($year, $month)
            ->create([
                'user_id' => $user->id,
                'category_id' => fn() => $expenseCategories->random()->id,
            ]);

        // Extra food expenses (more frequent)
        Expense::factory(fake()->numberBetween(5, 12))
            ->food()
            ->forMonth($year, $month)
            ->create([
                'user_id' => $user->id,
                'category_id' => fn() => $expenseCategories->where('name', 'Food')->first()?->id ?? $expenseCategories->random()->id,
            ]);

        // Extra transportation expenses
        Expense::factory(fake()->numberBetween(3, 8))
            ->transport()
            ->forMonth($year, $month)
            ->create([
                'user_id' => $user->id,
                'category_id' => fn() => $expenseCategories->where('name', 'Transportation')->first()?->id ?? $expenseCategories->random()->id,
            ]);

        // Installment expenses (occasionally)
        if (fake()->boolean(20)) {
            $totalInstallments = fake()->numberBetween(3, 12);
            $baseExpense = Expense::factory()
                ->installment($totalInstallments)
                ->forMonth($year, $month)
                ->make([
                    'user_id' => $user->id,
                    'category_id' => $expenseCategories->random()->id,
                    'description' => 'Installment purchase - ' . fake()->randomElement(['Electronics', 'Furniture', 'Appliance']),
                ]);

            // Create all installments
            for ($installment = 1; $installment <= $totalInstallments; $installment++) {
                $installmentDate = Carbon::create($year, $month)->addMonths($installment - 1);

                // Only create if installment date is within our period
                if ($installmentDate <= Carbon::now()->addMonths(3)) {
                    Expense::factory()->create([
                        'user_id' => $user->id,
                        'category_id' => $baseExpense->category_id,
                        'amount' => $baseExpense->amount,
                        'description' => $baseExpense->description . " ({$installment}/{$totalInstallments})",
                        'date' => $installmentDate,
                        'transaction_group_uuid' => $baseExpense->transaction_group_uuid,
                        'installment_number' => $installment,
                        'total_installments' => $totalInstallments,
                    ]);
                }
            }
        }
    }
}
