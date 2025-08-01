<?php

namespace App\Livewire\Categories;

use App\Enums\CategoryType;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

#[Title('Nova Categoria')]
class Create extends Component
{
    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate('required', new Enum(CategoryType::class))]
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

        session()->flash('success', 'Categoria criada com sucesso!');
        return $this->redirect('/categories', navigate: true);
    }

    public function render()
    {
        return view('livewire.categories.create');
    }
}