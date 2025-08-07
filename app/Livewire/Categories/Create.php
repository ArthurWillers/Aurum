<?php

namespace App\Livewire\Categories;

use App\Enums\CategoryType;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate(['required', new Enum(CategoryType::class)])]
    public string $type = '';

    /**
     * Método para salvar a nova categoria.
     */
    public function save()
    {
        $this->validate();

        Auth::user()->categories()->create([
            'name' => $this->name,
            'type' => $this->type,
        ]);

        // Toast para redirecionamento
        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Categoria criada com sucesso!'
        ]);

        return $this->redirect('/categories', navigate: true);
    }

    public function render()
    {
        return view('livewire.categories.create');
    }
}
