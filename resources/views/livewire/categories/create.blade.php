<div>
    <form wire:submit="save" class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                {{ __('New Category') }}
            </h2>
            <flux:button href="{{ route('categories.index') }}" wire:navigate.persist>{{ __('Back') }}</flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex flex-col gap-6">

                <flux:input wire:model="name" :label="__('Name')" id="name"
                    :placeholder="__('Ex: Salary, Supermarket, Leisure')" autofocus />

                <flux:radio.group wire:model="type" :label="__('Type')" class="py-1">
                    <flux:radio value="expense" :label="__('Expense')" />
                    <flux:radio value="income" :label="__('Income')" />
                </flux:radio.group>

                <div class="flex justify-between">
                    <flux:button href="{{ route('categories.index') }}" wire:navigate.persist>
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ __('Save') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
