<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    public function render()
    {
        $categories = Auth::user()->categories()->latest()->get();

        return view('livewire.categories.index', compact('categories'));
    }

    public function delete(Category $category)
    {
        try {
            $this->authorize('delete', $category);

            if ($category->expenses()->count() > 0 || $category->incomes()->count() > 0) {
                // Toast para erro - ação na mesma página
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'message' => __('Cannot delete category with associated transactions.')
                ]);
                return;
            }

            $category->delete();

            // Toast para sucesso - ação na mesma página
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => __('Category deleted successfully!')
            ]);

            // Dispatch event para atualizar outros componentes se necessário
            $this->dispatch('category-deleted');
        } catch (\Exception $e) {
            // Log do erro para debug
            Log::error('Erro ao deletar categoria: ' . $e->getMessage());

            // Toast para erro inesperado
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => __('An unexpected error occurred. Please try again.')
            ]);
        }
    }
}
