<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Models\Category;
use App\Enums\CategoryType;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public $selectedCategory = '';

    /**
     * Redefine a paginação quando o filtro de categoria mudar.
     */
    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    /**
     * Limpa o filtro de categoria.
     */
    public function clearCategoryFilter()
    {
        $this->selectedCategory = '';
        $this->resetPage();
    }

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

            // Toast para sucesso - ação na mesma página
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => __('Expense deleted successfully!')
            ]);

            // Dispatch event para atualizar outros componentes se necessário
            $this->dispatch('expense-deleted');
        } catch (\Exception $e) {
            // Log do erro para debug
            Log::error('Erro ao deletar despesa: ' . $e->getMessage());

            // Toast para erro inesperado
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => __('An unexpected error occurred. Please try again.')
            ]);
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

        $expensesQuery = Auth::user()
            ->expenses()
            ->with('category')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month);

        // Aplicar filtro de categoria se selecionado
        if ($this->selectedCategory) {
            $expensesQuery->where('category_id', $this->selectedCategory);
        }

        $expenses = $expensesQuery
            ->latest('date')
            ->paginate(10);

        // Buscar categorias de despesa para o filtro
        $categories = Auth::user()
            ->categories()
            ->where('type', CategoryType::Expense)
            ->orderBy('name')
            ->get();

        return view('livewire.expenses.index', [
            'expenses' => $expenses,
            'categories' => $categories,
        ]);
    }
}
