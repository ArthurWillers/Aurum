<div>
    <x-toast />

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Despesas</h2>
        <flux:button href="{{ route('expenses.create') }}" wire:navigate.persist icon="plus">
            Nova Despesa
        </flux:button>
    </div>

    <div
        class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 overflow-x-auto">
        <table class="w-full min-w-full text-left">
            <thead class="border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Descrição</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Valor</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Categoria</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Data</th>
                    <th
                        class="px-6 py-3 text-end text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($expenses as $expense)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-white">
                            {{ $expense->description }}
                            @if ($expense->total_installments)
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                    ({{ $expense->installment_number }}/{{ $expense->total_installments }})
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 font-semibold">
                            - R$ {{ number_format($expense->amount, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $expense->category->name ?? 'Sem categoria' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $expense->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <flux:dropdown position="bottom" align="end">
                                <flux:button icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('expenses.edit', $expense) }}" wire:navigate.persist
                                        icon="pencil">Editar</flux:menu.item>
                                    <flux:menu.item wire:click.prevent="delete({{ $expense->id }})" icon="trash"
                                        variant="danger">Excluir</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-600 dark:text-zinc-200">
                            Você ainda não cadastrou nenhuma despesa nesse mês.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $expenses->links() }}
    </div>
</div>
