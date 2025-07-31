<div x-data="{ expenseType: @entangle('expenseType') }">
    <form wire:submit="save" class="flex flex-col gap-6">

        {{-- Cabeçalho --}}
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Nova Despesa
            </h2>
            <flux:button href="{{ route('expenses.index') }}" wire:navigate.persist>Voltar</flux:button>
        </div>

        {{-- Card do Formulário --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex flex-col gap-6">

                {{-- 2. Seletor do Tipo de Despesa --}}
                <flux:radio.group wire:model.live="expenseType" label="Tipo de Despesa" variant="segmented">
                    <flux:radio value="single" label="Única" />
                    <flux:radio value="recurring" label="Recorrente" />
                    <flux:radio value="installment" label="Parcelada" />
                </flux:radio.group>

                <flux:input wire:model="description" label="Descrição" placeholder="Ex: Compras no Supermercado" autofocus />

                {{-- 3. Campos que mudam dinamicamente com x-show --}}
                <div x-show="expenseType === 'single' || expenseType === 'recurring'">
                    <flux:input wire:model="amount" label="Valor (R$)" type="number" step="0.01" placeholder="150.00" />
                </div>

                <div x-show="expenseType === 'installment'">
                    <flux:input wire:model="total_amount" label="Valor Total (R$)" type="number" step="0.01" placeholder="1200.00" />
                </div>

                <flux:input wire:model="date" label="Data da Compra / Início" type="date"/>

                <div x-show="expenseType === 'recurring'">
                    <flux:input wire:model="months" label="Duração (meses)" type="number" placeholder="12" />
                </div>

                <div x-show="expenseType === 'installment'">
                    <flux:input wire:model="installments" label="Número de Parcelas" type="number" placeholder="10" />
                </div>

                <div class="grid gap-2">
                    <label for="category_id" class="font-medium text-sm text-zinc-700 dark:text-zinc-200">Categoria</label>
                    <flux:select wire:model="category_id" id="category_id">
                        <option value="" disabled>Selecione uma categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Botões de Ação --}}
                <div class="flex justify-between pt-4">
                    <flux:button href="{{ route('expenses.index') }}" wire:navigate.persist>
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Salvar Despesa
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>