<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalMonthlyIncomes = 0;
    public $totalMonthlyExpenses = 0;
    public $monthlyBalance = 0;

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

    private function loadData()
    {
        // valores do mês atual
        $this->totalMonthlyIncomes = $this->getMonthlyIncomes();
        $this->totalMonthlyExpenses = $this->getMonthlyExpenses();
        $this->monthlyBalance = $this->totalMonthlyIncomes - $this->totalMonthlyExpenses;
        
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
