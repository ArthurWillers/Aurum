<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

#[Title('Despesas')]
class Index extends Component
{
    use WithPagination;

    /**
     * Exclui uma despesa.
     */
    public function delete(Expense $expense)
    {
        try {
            $this->authorize('delete', $expense);

            $expense->delete();

            // Reset pagination se necessário
            $this->resetPage();

            // Dispatch event para atualizar outros componentes se necessário
            $this->dispatch('expense-deleted');
        } catch (\Exception $e) {
            // Log do erro para debug
            Log::error('Erro ao deletar despesa: ' . $e->getMessage());
        }
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
