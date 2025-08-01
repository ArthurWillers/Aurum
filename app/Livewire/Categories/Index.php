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
        if ($category->user_id !== Auth::id()) {
            session()->flash('error', 'Você não tem permissão para excluir esta categoria.');
            return;
        }
        if ($category->expenses()->count() > 0 || $category->incomes()->count() > 0) {
            session()->flash('error', 'Não é possível excluir a categoria porque ela possui despesas ou receitas associadas.');
            return;
        }

        $category->delete();

        session()->flash('success', 'Categoria excluída com sucesso.');
    }
}
