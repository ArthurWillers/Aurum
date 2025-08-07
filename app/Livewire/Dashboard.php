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

    // Propriedades para o pódio financeiro
    public array $topExpenses = [];
    public array $topIncomes = [];

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
        $this->loadPodiumData();
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
     * Carrega dados do pódio financeiro (top 3 despesas e receitas por categoria)
     */
    private function loadPodiumData(): void
    {
        $user = Auth::user();
        $year = $this->selectedDate->year;
        $month = $this->selectedDate->month;

        // Top 3 Despesas por categoria
        $topExpensesData = $user->expenses()
            ->with('category')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereHas('category')
            ->selectRaw('category_id, SUM(amount) as total_amount')
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->limit(3)
            ->get()
            ->map(function ($expense) use ($user) {
                return [
                    'name' => $expense->category->name,
                    'value' => ($user->currency_symbol ?? 'R$') . ' ' . number_format($expense->total_amount, 2, ',', '.'),
                    'raw_value' => $expense->total_amount
                ];
            })
            ->toArray();

        $this->topExpenses = [
            'items' => $topExpensesData,
            'total' => $this->totalMonthlyExpenses
        ];

        // Top 3 Receitas por categoria
        $topIncomesData = $user->incomes()
            ->with('category')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereHas('category')
            ->selectRaw('category_id, SUM(amount) as total_amount')
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->limit(3)
            ->get()
            ->map(function ($income) use ($user) {
                return [
                    'name' => $income->category->name,
                    'value' => ($user->currency_symbol ?? 'R$') . ' ' . number_format($income->total_amount, 2, ',', '.'),
                    'raw_value' => $income->total_amount
                ];
            })
            ->toArray();

        $this->topIncomes = [
            'items' => $topIncomesData,
            'total' => $this->totalMonthlyIncomes
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
