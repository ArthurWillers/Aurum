<?php

use App\Models\User;
use App\Models\Income;
use App\Models\Category;
use App\Enums\CategoryType;
use Carbon\Carbon;

describe('IncomeFactory', function () {
    it('creates an income with valid data', function () {
        $income = Income::factory()->make();

        expect($income->description)->toBeString()
            ->and($income->amount)->toBeFloat()
            ->and($income->amount)->toBeGreaterThan(0)
            ->and($income->date)->toBeInstanceOf(Carbon::class)
            ->and($income->user_id)->toBeInt()
            ->and($income->category_id)->toBeInt();
    });

    it('creates income with amount in valid range', function () {
        $income = Income::factory()->make();

        expect($income->amount)->toBeGreaterThanOrEqual(100)
            ->and($income->amount)->toBeLessThanOrEqual(15000);
    });

    it('creates income with date in valid range', function () {
        $income = Income::factory()->make();
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $threeMonthsAhead = Carbon::now()->addMonths(3);

        expect($income->date)->toBeGreaterThanOrEqual($sixMonthsAgo)
            ->and($income->date)->toBeLessThanOrEqual($threeMonthsAhead);
    });

    it('creates salary income with proper characteristics', function () {
        $income = Income::factory()->salary()->make();

        expect($income->description)->toBe('Monthly salary')
            ->and($income->amount)->toBeGreaterThanOrEqual(3000)
            ->and($income->amount)->toBeLessThanOrEqual(12000);
    });

    it('creates freelance income with proper characteristics', function () {
        $income = Income::factory()->freelance()->make();
        $validProjects = ['Corporate website', 'Mobile app', 'Management system', 'E-commerce', 'Landing page', 'Technical consulting', 'System maintenance'];

        expect($income->description)->toStartWith('Freelance - ')
            ->and($income->amount)->toBeGreaterThanOrEqual(500)
            ->and($income->amount)->toBeLessThanOrEqual(8000);

        $projectName = str_replace('Freelance - ', '', $income->description);
        expect($validProjects)->toContain($projectName);
    });

    it('creates income for specific month', function () {
        $year = 2025;
        $month = 8;
        $income = Income::factory()->forMonth($year, $month)->make();

        expect($income->date->year)->toBe($year)
            ->and($income->date->month)->toBe($month);
    });

    it('creates income with proper relationships', function () {
        $user = User::factory()->create();
        $category = Category::factory()->income()->create(['user_id' => $user->id]);
        
        $income = Income::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        expect($income->user->id)->toBe($user->id)
            ->and($income->category->id)->toBe($category->id)
            ->and($income->category->type)->toBe(CategoryType::Income);
    });

    it('generates realistic income descriptions', function () {
        $validDescriptions = [
            'Monthly salary', 'Freelance - Web project', 'Technical consulting', 'Product sales',
            'Investment dividends', 'Property rental', 'Annual bonus', 'Overtime work',
            'Cashback', 'Money gift', 'Refund', 'Sales commission', 'Investment returns',
            'Online monetization', 'Service provided'
        ];

        $income = Income::factory()->make();
        
        // For freelance, we need to check if it starts with 'Freelance -' or is in the list
        $isValid = in_array($income->description, $validDescriptions) || 
                   str_starts_with($income->description, 'Freelance - ');
        
        expect($isValid)->toBeTrue();
    });
});
