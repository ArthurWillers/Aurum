<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public Expense $expense;

    #[Validate('required|string|min:3|max:255')]
    public string $description = '';

    #[Validate('required|numeric|min:0.01')]
    public string $amount = '';

    #[Validate('required|date')]
    public string $date = '';

    #[Validate('required|exists:categories,id')]
    public string $category_id = '';

    /**
     * Carrega a despesa e preenche o formulário.
     */
    public function mount(Expense $expense)
    {
        $this->authorize('update', $expense);

        $this->expense = $expense;

        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->date = $expense->date->format('Y-m-d');
        $this->category_id = $expense->category_id;
    }

    /**
     * Atualiza os dados da despesa no banco.
     */
    public function update()
    {
        $this->authorize('update', $this->expense);

        $this->validate();

        $this->expense->update([
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'category_id' => $this->category_id,
        ]);

        // Toast para redirecionamento
        session()->flash('toast', [
            'type' => 'success',
            'message' => __('Expense updated successfully!')
        ]);

        return $this->redirectRoute('expenses.index', navigate: true);
    }

    /**
     * Renderiza a view.
     */
    public function render()
    {
        $categories = Auth::user()->categories()->where('type', 'expense')->get();
        return view('livewire.expenses.edit', ['categories' => $categories]);
    }
}
