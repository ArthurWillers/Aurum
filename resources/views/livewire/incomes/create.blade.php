<div>
    <form wire:submit="save" class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Nova Receita
            </h2>
            <flux:button href="{{ route('incomes.index') }}" wire:navigate.persist>Voltar</flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex flex-col gap-6">

                <flux:input wire:model="description" label="Descrição da Receita"
                    placeholder="Ex: Salário, Venda de item, Freelance" autofocus />

                <flux:input wire:model="amount" label="Valor (R$)" type="number" step="0.01"
                    placeholder="1500.00" />

                <flux:input wire:model="date" label="Data de Recebimento" type="date" />
                <div class="grid gap-2">
                    <label for="category_id" class="font-medium text-sm text-zinc-700 dark:text-zinc-200">
                        Categoria
                    </label>
                    <flux:select wire:model="category_id" id="category_id">
                        <option value="" disabled>Selecione uma categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                    @error('category_id')
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between pt-4">
                    <flux:button href="{{ route('incomes.index') }}" wire:navigate.persist>
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Salvar Receita
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
