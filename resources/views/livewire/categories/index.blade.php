<div>
    <div class="flex justify-between items-center mb-6">
        <flux:heading class="text-xl">Categorias</flux:heading>

        <flux:button href="{{ route('categories.create') }}" wire:navigate.persist icon="plus">Nova
            Categoria </flux:button>
    </div>

    <div
        class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 overflow-x-auto">
        <table class="w-full min-w-full text-left">
            <thead class="border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-600 dark:text-zinc-200 uppercase tracking-wider">
                        Nome
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-600 dark:text-zinc-200 uppercase tracking-wider">
                        Tipo
                    </th>
                    <th
                        class="px-6 py-3 text-end text-xs font-medium text-zinc-600 dark:text-zinc-200 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($categories as $category)
                    <tr wire:key="category-{{ $category->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-white">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <flux:badge :color="$category->isIncome() ? 'green' : 'red'">
                                {{ $category->isIncome() ? 'Receita' : 'Despesa' }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <flux:dropdown position="bottom" align="end">
                                <flux:button icon="ellipsis-horizontal" class="cursor-pointer" />

                                <flux:menu>
                                    <flux:menu.item href="{{ route('categories.edit', $category) }}"
                                        wire:navigate.persist icon="pencil" iconVariant="outline" label="Editar">Editar
                                    </flux:menu.item>

                                    <flux:menu.item wire:click="delete({{ $category->id }})"
                                        wire:confirm="Tem certeza que deseja excluir esta categoria?"
                                        class="cursor-pointer" icon="trash" iconVariant="outline" variant="danger">
                                        Excluir</flux:menu.item>
                                </flux:menu>

                            </flux:dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-sm text-zinc-600 dark:text-zinc-200">
                            Você ainda não cadastrou nenhuma categoria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
</div>
