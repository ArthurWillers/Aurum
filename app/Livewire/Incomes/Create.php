<?php

namespace App\Livewire\Incomes;

use Carbon\Carbon;
use Livewire\Component;

use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    #[Validate('required|string|min:3|max:255')]
    public string $description = '';

    #[Validate('required|numeric|min:0.01')]
    public string $amount = '';

    #[Validate('required|date')]
    public string $date = '';

    #[Validate('required|exists:categories,id')]
    public string $category_id = '';

    /**
     * Método executado quando o componente é inicializado.
     */
    public function mount()
    {
        $selectedMonth = session('selected_month', now()->format('Y-m'));
        if ($selectedMonth === now()->format('Y-m')) {
            $this->date = now()->format('Y-m-d');
        } else {
            $this->date = Carbon::parse($selectedMonth . '-01')->format('Y-m-d');
        }
    }

    /**
     * Salva a nova receita no banco de dados.
     */
    public function save()
    {
        $this->validate();

        Auth::user()->incomes()->create([
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'category_id' => $this->category_id,
        ]);

        // Toast para redirecionamento
        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Receita registrada com sucesso!'
        ]);

        return $this->redirectRoute('incomes.index', navigate: true);
    }

    /**
     * Renderiza a view.
     */
    public function render()
    {
        $categories = Auth::user()
            ->categories()
            ->where('type', 'income')
            ->get();

        return view('livewire.incomes.create', [
            'categories' => $categories,
        ]);
    }
}
