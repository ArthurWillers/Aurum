<div>
    <form wire:submit="update" class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Edit Income') }}</h2>
            <flux:button href="{{ route('incomes.index') }}" wire:navigate.persist>{{ __('Back') }}</flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex flex-col gap-6">
                <flux:input wire:model="description" :label="__('Description')" id="description"
                    placeholder="Ex: Salary, Sale, etc." autofocus />
                <flux:input wire:model="amount" :label="__('Value')" id="amount" type="number" step="0.01"
                    min="0.01" prefix="R$" />
                <flux:input wire:model="date" :label="__('Date')" id="date" type="date" />
                <flux:select wire:model="category_id" :label="__('Category')" id="category_id">
                    <option value="">{{ __('Select...') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </flux:select>
                <div class="flex justify-between">
                    <flux:button href="{{ route('incomes.index') }}" wire:navigate.persist>{{ __('Cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
