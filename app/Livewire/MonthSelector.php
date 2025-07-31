<?php

namespace App\Livewire;

use Livewire\Component;

class MonthSelector extends Component
{
    public string $selectedMonth;

    public function mount()
    {
        $this->selectedMonth = session('selected_month', now()->format('Y-m'));
    }

    public function updatedSelectedMonth($value)
    {
        session(['selected_month' => $value]);

        $this->dispatch('month-changed');
    }

    public function render()
    {
        return view('livewire.month-selector');
    }
}