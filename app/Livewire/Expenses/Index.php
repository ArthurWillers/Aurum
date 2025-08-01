<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Title('Despesas')]
class Index extends Component
{
    use WithPagination;

    /**
     * Exclui uma despesa.
     */
    public function delete(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->delete();
    }

    /**
     * Renderiza o componente.
     */
    #[On('month-changed')]
    public function render()
    {
        $selectedMonth = session('selected_month', now()->format('Y-m'));
        $date = Carbon::parse($selectedMonth);

        $expenses = Auth::user()
            ->expenses()
            ->with('category')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month)
            ->latest('date')
            ->paginate(10);

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
        ]);
    }
}
