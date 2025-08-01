<div>
    <form wire:submit="update" class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Editar Categoria
            </h2>
            <flux:button href="{{ route('categories.index') }}" wire:navigate.persist>Voltar</flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex flex-col gap-6">

                <flux:input wire:model="name" label="Nome da Categoria" id="name"
                    placeholder="Ex: Salário, Supermercado, Lazer" autofocus />

                <flux:radio.group wire:model="type" label="Tipo" class="py-1">
                    <flux:radio value="expense" label="Despesa" />
                    <flux:radio value="income" label="Receita" />
                </flux:radio.group>

                <div class="flex justify-between">
                    <flux:button href="{{ route('categories.index') }}" wire:navigate.persist>
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Salvar
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
