<?php

namespace App\Livewire\Expenses;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    #[Validate('required|string|min:3|max:255')]
    public string $description = '';

    #[Validate('required|exists:categories,id')]
    public string $category_id = '';

    public string $amount = '';
    public string $date = '';
    public string $total_amount = '';
    public ?int $installments = null;
    public ?int $months = null;
    public string $expenseType = 'single'; // 'single', 'recurring', 'installment'

    /**
     * Define os valores iniciais do formulário.
     */
    public function mount()
    {
        $selectedMonth = session('selected_month', now()->format('Y-m'));
        $this->date = Carbon::parse($selectedMonth . '-01')->format('Y-m-d');
    }

    /**
     * Salva a(s) despesa(s) no banco de dados.
     */
    public function save()
    {
        $rules = [
            'description' => 'required|string|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
        ];

        if ($this->expenseType === 'single' || $this->expenseType === 'recurring') {
            $rules['amount'] = 'required|numeric|min:0.01';
        }

        if ($this->expenseType === 'recurring') {
            $rules['months'] = 'required|integer|min:2|max:36';
        }

        if ($this->expenseType === 'installment') {
            $rules['total_amount'] = 'required|numeric|min:0.01';
            $rules['installments'] = 'required|integer|min:2|max:36';
        }

        $this->validate($rules);

        switch ($this->expenseType) {
            case 'single':
                $this->saveSingleExpense();
                break;
            case 'recurring':
                $this->saveRecurringExpense();
                break;
            case 'installment':
                $this->saveInstallmentExpense();
                break;
        }

        // Toast para redirecionamento
        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Despesa registrada com sucesso!'
        ]);

        return $this->redirectRoute('expenses.index', navigate: true);
    }

    private function saveSingleExpense()
    {
        Auth::user()->expenses()->create([
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'category_id' => $this->category_id,
        ]);
    }

    private function saveRecurringExpense()
    {
        $startDate = Carbon::parse($this->date);
        for ($i = 0; $i < $this->months; $i++) {
            Auth::user()->expenses()->create([
                'description' => $this->description,
                'amount' => $this->amount,
                'date' => $startDate->copy()->addMonths($i),
                'category_id' => $this->category_id,
            ]);
        }
    }

    private function saveInstallmentExpense()
    {
        $installmentAmount = $this->total_amount / $this->installments;
        $groupId = Str::uuid();
        $startDate = Carbon::parse($this->date);

        for ($i = 1; $i <= $this->installments; $i++) {
            Auth::user()->expenses()->create([
                'description' => $this->description,
                'amount' => $installmentAmount,
                'date' => $startDate->copy()->addMonths($i - 1),
                'category_id' => $this->category_id,
                'transaction_group_uuid' => $groupId,
                'installment_number' => $i,
                'total_installments' => $this->installments,
            ]);
        }
    }

    /**
     * Renderiza a view.
     */
    public function render()
    {
        $categories = Auth::user()->categories()->where('type', 'expense')->get();
        return view('livewire.expenses.create', ['categories' => $categories]);
    }
}
