<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Category;
use App\Enums\CategoryType;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    public Category $category;

    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate(['required', new Enum(CategoryType::class)])]
    public string $type = '';

    public function mount()
    {
        $this->authorize('update', $this->category);

        $this->name = $this->category->name;
        $this->type = $this->category->type->value;
    }

    public function update()
    {
        $this->authorize('update', $this->category);

        $this->validate();
        $this->category->update([
            'name' => $this->name,
            'type' => $this->type,
        ]);
        return $this->redirect('/categories', navigate: true);
    }

    public function render()
    {
        return view('livewire.categories.edit');
    }
}
