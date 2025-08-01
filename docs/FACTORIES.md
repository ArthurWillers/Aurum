# Database Factories

This document describes the factories available in the project for generating test data.

## Available Factories

### UserFactory
- Creates users with realistic names and emails
- Default password: `password` (hashed)
- Includes `unverified()` state for testing

### CategoryFactory
- Creates categories for both income and expense types
- Realistic category names in English
- Methods:
  - `income()` - Creates income categories (Salary, Freelance, Investments, etc.)
  - `expense()` - Creates expense categories (Food, Transportation, Housing, etc.)

### IncomeFactory
- Creates income records with realistic descriptions and amounts
- Amount range: $100 - $15,000
- Date range: 6 months ago to 3 months in the future
- Methods:
  - `salary()` - Monthly salary income ($3,000 - $12,000)
  - `freelance()` - Freelance project income ($500 - $8,000)
  - `forMonth(year, month)` - Income for specific month

### ExpenseFactory
- Creates expense records with realistic descriptions and amounts
- Amount range: $10 - $2,000
- Date range: 6 months ago to 3 months in the future
- Methods:
  - `recurring()` - Monthly recurring expenses ($50 - $800)
  - `food()` - Food-related expenses ($15 - $200)
  - `transport()` - Transportation expenses ($10 - $300)
  - `installment(totalInstallments)` - Installment purchases
  - `forMonth(year, month)` - Expense for specific month

## Usage Examples

### Creating Test Data

```php
// Create a user with categories and transactions
$user = User::factory()->create();

// Create categories
$incomeCategories = Category::factory(5)->income()->create(['user_id' => $user->id]);
$expenseCategories = Category::factory(8)->expense()->create(['user_id' => $user->id]);

// Create incomes
Income::factory(10)->create([
    'user_id' => $user->id,
    'category_id' => $incomeCategories->random()->id
]);

// Create expenses
Expense::factory(20)->create([
    'user_id' => $user->id,
    'category_id' => $expenseCategories->random()->id
]);
```

### Specific Types

```php
// Create salary income
Income::factory()->salary()->create(['user_id' => $user->id]);

// Create food expenses
Expense::factory(5)->food()->create(['user_id' => $user->id]);

// Create installment purchase
Expense::factory()->installment(12)->create(['user_id' => $user->id]);
```

### Monthly Data

```php
// Create data for specific month
Income::factory(3)->forMonth(2025, 8)->create(['user_id' => $user->id]);
Expense::factory(15)->forMonth(2025, 8)->create(['user_id' => $user->id]);
```

## Database Seeder

The `DatabaseSeeder` class creates:
- 1 Test User (email: test@example.com, password: 123)
- 3 Additional users with random data
- For each user:
  - 5 income categories
  - 8 expense categories
  - Realistic transaction data spanning 9 months (6 months ago to 3 months ahead)
  - Monthly salary incomes
  - Occasional freelance incomes
  - Fixed monthly expenses (rent, fuel, groceries)
  - Variable expenses (food, transportation, entertainment)
  - Occasional installment purchases

## Running the Seeder

```bash
# Fresh migration with seeded data
php artisan migrate:fresh --seed

# Or just run the seeder
php artisan db:seed
```

## Testing

All factories have comprehensive tests located in:
- `tests/Feature/CategoryFactoryTest.php`
- `tests/Feature/IncomeFactoryTest.php`
- `tests/Feature/ExpenseFactoryTest.php`
- `tests/Feature/DatabaseSeederTest.php`

Run factory tests:
```bash
./vendor/bin/pest tests/Feature/*FactoryTest.php
./vendor/bin/pest tests/Feature/DatabaseSeederTest.php
```
