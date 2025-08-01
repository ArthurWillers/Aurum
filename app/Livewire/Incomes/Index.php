<?php

namespace App\Livewire\Incomes;

use App\Models\Income;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    /**
     * Exclui uma receita.
     */
    public function delete(Income $income)
    {
        try {
            $this->authorize('delete', $income);

            $income->delete();

            // Reset pagination se necessário
            $this->resetPage();

            // Dispatch event para atualizar outros componentes se necessário
            $this->dispatch('income-deleted');
        } catch (\Exception $e) {
            // Log do erro para debug
            Log::error('Erro ao deletar receita: ' . $e->getMessage());
        }
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
