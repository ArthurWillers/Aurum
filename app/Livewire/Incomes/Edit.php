<?php

namespace App\Livewire\Incomes;

use Livewire\Component;
use App\Models\Income;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

#[Title('Editar Receita')]
class Edit extends Component
{
    public Income $income;

    #[Validate('required|string|min:3|max:255')]
    public string $description = '';

    #[Validate('required|numeric|min:0.01')]
    public string $amount = '';

    #[Validate('required|date')]
    public string $date = '';

    #[Validate('required|exists:categories,id')]
    public string $category_id = '';

    public function mount()
    {
        $this->description = $this->income->description;
        $this->amount = $this->income->amount;
        $this->date = $this->income->date->format('Y-m-d');
        $this->category_id = $this->income->category_id;
    }

    public function update()
    {
        $this->validate();
        $this->income->update([
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'category_id' => $this->category_id,
        ]);
        session()->flash('success', 'Receita atualizada com sucesso!');
        return $this->redirectRoute('incomes.index', navigate: true);
    }

    public function render()
    {
        $categories = Auth::user()
            ->categories()
            ->where('type', 'income')
            ->get();

        return view('livewire.incomes.edit', [
            'categories' => $categories,
        ]);
    }
}
