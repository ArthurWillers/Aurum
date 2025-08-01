<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Incomes') }}</h2>
        <flux:button href="{{ route('incomes.create') }}" wire:navigate.persist icon="plus">
            {{ __('New Income') }}
        </flux:button>
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
                @forelse ($incomes as $income)
                    <tr wire:key="income-{{ $income->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-white">
                            {{ $income->description }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-semibold">
                            + {{ auth()->user()->currency_symbol ?? '$' }}
                            {{ number_format($income->amount, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $income->category->name ?? __('No category') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $income->date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <flux:dropdown position="bottom" align="end">
                                <flux:button icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item href="{{ route('incomes.edit', $income) }}" wire:navigate.persist
                                        icon="pencil" :label="__('Edit')">{{ __('Edit') }}</flux:menu.item>
                                    <flux:menu.item wire:click="delete({{ $income->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this income?') }}"
                                        icon="trash" variant="danger" :label="__('Delete')">{{ __('Delete') }}
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-zinc-600 dark:text-zinc-200">
                            {{ __('You haven\'t registered any income this month.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $incomes->links() }}
    </div>
</div>
