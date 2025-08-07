<?php

namespace App\Livewire\Incomes;

use App\Models\Income;
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
     * Exclui uma receita.
     */
    public function delete(Income $income)
    {
        try {
            $this->authorize('delete', $income);

            $income->delete();

            // Reset pagination se necessário
            $this->resetPage();

            // Toast para sucesso - ação na mesma página
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Receita excluída com sucesso!'
            ]);

            // Dispatch event para atualizar outros componentes se necessário
            $this->dispatch('income-deleted');
        } catch (\Exception $e) {
            // Log do erro para debug
            Log::error('Erro ao deletar receita: ' . $e->getMessage());

            // Toast para erro inesperado
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Ocorreu um erro inesperado. Tente novamente.'
            ]);
        }
    }

    #[On('month-changed')]
    public function render()
    {
        $selectedMonth = session('selected_month', now()->format('Y-m'));
        $date = Carbon::parse($selectedMonth);

        $incomesQuery = Auth::user()
            ->incomes()
            ->with('category')
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month);

        // Aplicar filtro de categoria se selecionado
        if ($this->selectedCategory) {
            $incomesQuery->where('category_id', $this->selectedCategory);
        }

        $incomes = $incomesQuery
            ->latest('date')
            ->paginate(10);

        // Buscar categorias de receita para o filtro
        $categories = Auth::user()
            ->categories()
            ->where('type', CategoryType::Income)
            ->orderBy('name')
            ->get();

        return view('livewire.incomes.index', [
            'incomes' => $incomes,
            'categories' => $categories,
        ]);
    }
}
