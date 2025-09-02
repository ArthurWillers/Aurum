<div>
    <div class="flex justify-between items-center mb-6">
        <flux:heading size="xl">{{ __('Categories') }}</flux:heading>

        <flux:button href="{{ route('categories.create') }}" wire:navigate.persist icon="plus">{{ __('New Category') }}
        </flux:button>
    </div>

    <div
        class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 overflow-x-auto">
        <table class="w-full min-w-full text-left">
            <thead class="border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-600 dark:text-zinc-200 uppercase tracking-wider">
                        {{ __('Name') }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-600 dark:text-zinc-200 uppercase tracking-wider">
                        {{ __('Type') }}
                    </th>
                    <th
                        class="px-6 py-3 text-end text-xs font-medium text-zinc-600 dark:text-zinc-200 uppercase tracking-wider">
                        {{ __('Actions') }}
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
                                {{ $category->isIncome() ? __('Income') : __('Expense') }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <flux:dropdown position="bottom" align="end">
                                <flux:button icon="ellipsis-horizontal" class="cursor-pointer" />

                                <flux:menu>
                                    <flux:menu.item href="{{ route('categories.edit', $category) }}"
                                        wire:navigate.persist icon="pencil" iconVariant="outline"
                                        :label="__('Edit')">{{ __('Edit') }}
                                    </flux:menu.item>

                                    <flux:menu.item wire:click="delete({{ $category->id }})"
                                        wire:confirm="{{ __('Are you sure you want to delete this category?') }}"
                                        class="cursor-pointer" icon="trash" iconVariant="outline" variant="danger">
                                        {{ __('Delete') }}</flux:menu.item>
                                </flux:menu>

                            </flux:dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-sm text-zinc-600 dark:text-zinc-200">
                            {{ __('You haven\'t registered any categories yet.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
