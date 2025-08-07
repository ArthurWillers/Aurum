<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Expenses') }}</h2>
        <flux:button href="{{ route('expenses.create') }}" wire:navigate.persist icon="plus">
            {{ __('New Expense') }}
        </flux:button>
    </div>

    <!-- Filtro por categoria -->
    <div class="mb-4 flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
            <flux:select wire:model.live="selectedCategory" label="{{ __('Filter by category') }}">
                <option value="">{{ __('All categories') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </flux:select>
        </div>
        @if ($selectedCategory)
            <div class="pt-6 flex items-center">
                <flux:button wire:click="clearCategoryFilter" variant="subtle" icon="x-mark" size="base">
                    {{ __('Clear filter') }}
                </flux:button>
            </div>
        @endif
    </div>

    <div
        class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 overflow-x-auto">
        <table class="w-full min-w-full text-left">
            <thead class="border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        {{ __('Description') }}</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        {{ __('Value') }}</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        {{ __('Category') }}</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        {{ __('Date') }}</th>
                    <th
                        class="px-6 py-3 text-end text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">
                        {{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                @forelse ($expenses as $expense)
                    <tr wire:key="expense-{{ $expense->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-white">
                            {{ $expense->description }}
                            @if ($expense->total_installments)
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                    ({{ $expense->installment_number }}/{{ $expense->total_installments }})
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 dark:text-red-400 font-semibold">
                            - {{ auth()->user()->currency_symbol ?? '$' }}
                            {{ number_format($expense->amount, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $expense->category->name ?? __('No category') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $expense->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <flux:dropdown position="bottom" align="end">
                                <flux:button icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('expenses.edit', $expense) }}" wire:navigate.persist
                                        icon="pencil">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.item wire:click="delete({{ $expense->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this expense?') }}"
                                        icon="trash" variant="danger">{{ __('Delete') }}</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-600 dark:text-zinc-200">
                            {{ __('You haven\'t registered any expenses this month.') }}
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
