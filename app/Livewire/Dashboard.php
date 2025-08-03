<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalMonthlyIncomes = 0;
    public $totalMonthlyExpenses = 0;
    public $monthlyBalance = 0;
    public $MonthlyExpensesByCategoryLabels = [];
    public $MonthlyExpensesByCategoryData = [];
    public $MonthlyIncomesByCategoryLabels = [];
    public $MonthlyIncomesByCategoryData = [];
    public $evolutionLabels = [];
    public $evolutionIncomesData = [];
    public $evolutionExpensesData = [];
    public $evolutionBalanceData = [];

    public function mount()
    {
        $this->loadData();
    }

    #[On('month-changed')]
    public function refreshData()
    {
        $this->loadData();
    }

    private function getSelectedDate()
    {
        $selectedMonth = session('selected_month', now()->format('Y-m'));
        return Carbon::parse($selectedMonth);
    }

    private function getCurrentUser()
    {
        return Auth::user();
    }

    private function getMonthlyIncomes($date = null, $user = null)
    {
        $date = $date ?? $this->getSelectedDate();
        $user = $user ?? $this->getCurrentUser();

        return $user->incomes()
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->sum('amount');
    }

    private function getMonthlyExpenses($date = null, $user = null)
    {
        $date = $date ?? $this->getSelectedDate();
        $user = $user ?? $this->getCurrentUser();

        return $user->expenses()
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->sum('amount');
    }

    private function getMonthlyExpensesByCategory($date = null, $user = null)
    {
        $date = $date ?? $this->getSelectedDate();
        $user = $user ?? $this->getCurrentUser();

        return $user->expenses()
            ->with('category')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->get()
            ->groupBy('category.name')
            ->map(function ($expenses) {
                return $expenses->sum('amount');
            });
    }

    private function getMonthlyIncomesByCategory($date = null, $user = null)
    {
        $date = $date ?? $this->getSelectedDate();
        $user = $user ?? $this->getCurrentUser();

        return $user->incomes()
            ->with('category')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->get()
            ->groupBy('category.name')
            ->map(function ($incomes) {
                return $incomes->sum('amount');
            });
    }

    private function getFinancialEvolutionData($date = null, $user = null)
    {
        $date = $date ?? $this->getSelectedDate();
        $user = $user ?? $this->getCurrentUser();

        $evolutionData = [
            'labels' => [],
            'incomes' => [],
            'expenses' => [],
            'balance' => [],
        ];

        // Loop de 6 meses atrás até 1 mês à frente (total de 8 meses)
        for ($i = 6; $i >= -1; $i--) {
            $loopDate = $date->copy()->subMonths($i);

            // Adiciona o rótulo do mês (ex: "Ago/25")
            $evolutionData['labels'][] = $loopDate->translatedFormat('M/y');

            // Calcula e armazena o total de receitas daquele mês
            $income = $this->getMonthlyIncomes($loopDate, $user);
            $evolutionData['incomes'][] = $income;

            // Calcula e armazena o total de despesas daquele mês
            $expense = $this->getMonthlyExpenses($loopDate, $user);
            $evolutionData['expenses'][] = $expense;

            // Calcula e armazena o saldo
            $evolutionData['balance'][] = $income - $expense;
        }

        return $evolutionData;
    }


    private function loadData()
    {
        // valores do mês atual
        $this->totalMonthlyIncomes = $this->getMonthlyIncomes();
        $this->totalMonthlyExpenses = $this->getMonthlyExpenses();
        $this->monthlyBalance = $this->totalMonthlyIncomes - $this->totalMonthlyExpenses;

        // despesas por categoria
        $expensesData = $this->getMonthlyExpensesByCategory();
        $this->MonthlyExpensesByCategoryLabels = $expensesData->keys()->toArray();
        $this->MonthlyExpensesByCategoryData = $expensesData->values()->toArray();

        // receitas por categoria
        $incomesData = $this->getMonthlyIncomesByCategory();
        $this->MonthlyIncomesByCategoryLabels = $incomesData->keys()->toArray();
        $this->MonthlyIncomesByCategoryData = $incomesData->values()->toArray();

        // evolução financeira
        $evolution = $this->getFinancialEvolutionData();
        $this->evolutionLabels = $evolution['labels'];
        $this->evolutionIncomesData = $evolution['incomes'];
        $this->evolutionExpensesData = $evolution['expenses'];
        $this->evolutionBalanceData = $evolution['balance'];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
