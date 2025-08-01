<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $categories = Auth::user()->categories()->latest()->paginate(10);

        return view('livewire.categories.index', compact('categories'));
    }

    public function delete(Category $category)
    {
        try {
            $this->authorize('delete', $category);

            if ($category->expenses()->count() > 0 || $category->incomes()->count() > 0) {
                // Não é possível excluir categoria com registros associados
                return;
            }

            $category->delete();

            // Reset pagination se necessário
            $this->resetPage();

            // Dispatch event para atualizar outros componentes se necessário
            $this->dispatch('category-deleted');
        } catch (\Exception $e) {
            // Log do erro para debug
            Log::error('Erro ao deletar categoria: ' . $e->getMessage());
        }
    }
}
