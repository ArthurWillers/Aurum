<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Category;

#[Title('Categorias')]
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
        $this->authorize('delete', $category);

        if ($category->expenses()->count() > 0 || $category->incomes()->count() > 0) {
            // Não é possível excluir categoria com registros associados
            return;
        }

        $category->delete();
    }
}
