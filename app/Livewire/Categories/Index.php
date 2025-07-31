<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Categorias')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $categories = Auth::user()->categories()->paginate(8);

        return view('livewire.categories.index', compact('categories'));
    }
}
