<?php

namespace App\Livewire\Incomes;

use App\Models\Income;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Receitas')]
class Index extends Component
{
    use WithPagination;

    /**
     * Exclui uma receita.
     */
    public function delete(Income $income)
    {
        $this->authorize('delete', $income);

        $income->delete();

        session()->flash('success', 'Receita excluída com sucesso.');
    }

    #[On('month-changed')]
    public function render()
    {
        $selectedMonth = session('selected_month', now()->format('Y-m'));
        $date = Carbon::parse($selectedMonth);

        $incomes = Auth::user()
            ->incomes()
            ->with('category')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->latest('date')
            ->paginate(10);

        return view('livewire.incomes.index', [
            'incomes' => $incomes,
        ]);
    }
}
