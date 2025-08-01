<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalIncomes = 0;
    public $totalExpenses = 0;
    public $balance = 0;

    public function mount()
    {
        $this->loadCardData();
    }

    #[On('month-changed')]
    public function refreshData()
    {
        $this->loadCardData();
    }

    private function loadCardData()
    {
        $selectedMonth = session('selected_month', now()->format('Y-m'));
        $date = Carbon::parse($selectedMonth);
        $user = Auth::user();

        $this->totalIncomes = $user->incomes()
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->sum('amount');

        $this->totalExpenses = $user->expenses()
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->sum('amount');

        $this->balance = $this->totalIncomes - $this->totalExpenses;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
