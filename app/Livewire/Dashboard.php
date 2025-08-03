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

    public function render()
    {
        return view('livewire.dashboard');
    }
}
