<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Component
{
    // Propriedades para os cards de resumo
    public $totalMonthlyIncomes = 0;
    public $totalMonthlyExpenses = 0;
    public $monthlyBalance = 0;

    // Propriedades para os gráficos
    public array $expensesByCategory = [];
    public array $incomesByCategory = [];
    public array $financialEvolution = [];

    // Propriedade para data selecionada
    private Carbon $selectedDate;

    public function mount()
    {
        $this->loadAllData();
    }

    #[On('month-changed')]
    public function refreshData()
    {
        $this->loadAllData();
    }

    /**
     * Carrega todos os dados do dashboard
     */
    private function loadAllData(): void
    {
        $this->setSelectedDate();
        $this->loadCardData();
        $this->loadChartData();
    }

    /**
     * Define a data selecionada baseada na sessão
     */
    private function setSelectedDate(): void
    {
        $this->selectedDate = Carbon::parse(session('selected_month', now()->format('Y-m')));
    }

    /**
     * Carrega dados dos cards de resumo mensal
     */
    private function loadCardData(): void
    {
        $user = Auth::user();
        $year = $this->selectedDate->year;
        $month = $this->selectedDate->month;

        $this->totalMonthlyIncomes = $user->incomes()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $this->totalMonthlyExpenses = $user->expenses()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');

        $this->monthlyBalance = $this->totalMonthlyIncomes - $this->totalMonthlyExpenses;
    }

    /**
     * Carrega dados dos gráficos
     */
    private function loadChartData(): void
    {
        $this->loadExpensesByCategory();
        $this->loadIncomesByCategory();
        $this->loadFinancialEvolution();
    }

    /**
     * Carrega despesas agrupadas por categoria
     */
    private function loadExpensesByCategory(): void
    {
        $user = Auth::user();
        $year = $this->selectedDate->year;
        $month = $this->selectedDate->month;

        $expenses = $user->expenses()
            ->with('category')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $this->expensesByCategory = $expenses
            ->groupBy('category.name')
            ->map(fn($group) => $group->sum('amount'))
            ->sortDesc()
            ->toArray();
    }

    /**
     * Carrega receitas agrupadas por categoria
     */
    private function loadIncomesByCategory(): void
    {
        $user = Auth::user();
        $year = $this->selectedDate->year;
        $month = $this->selectedDate->month;

        $incomes = $user->incomes()
            ->with('category')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $this->incomesByCategory = $incomes
            ->groupBy('category.name')
            ->map(fn($group) => $group->sum('amount'))
            ->sortDesc()
            ->toArray();
    }

    /**
     * Carrega dados de evolução financeira.
     */
    private function loadFinancialEvolution(): void
    {
        $user = Auth::user();
        $evolutionData = [
            'labels' => [],
            'incomes' => [],
            'expenses' => [],
            'balance' => []
        ];

        for ($i = 6; $i >= -1; $i--) {
            $loopDate = $this->selectedDate->copy()->subMonths($i);

            $evolutionData['labels'][] = $loopDate->translatedFormat('M/y');

            $income = $user->incomes()
                ->whereYear('date', $loopDate->year)
                ->whereMonth('date', $loopDate->month)
                ->sum('amount');

            $expense = $user->expenses()
                ->whereYear('date', $loopDate->year)
                ->whereMonth('date', $loopDate->month)
                ->sum('amount');

            $evolutionData['incomes'][] = round($income, 2);
            $evolutionData['expenses'][] = round($expense, 2);
            $evolutionData['balance'][] = round($income - $expense, 2);
        }

        $this->financialEvolution = $evolutionData;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
