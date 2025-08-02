<div x-data="{ expenseType: @entangle('expenseType') }">
    <form wire:submit="save" class="flex flex-col gap-6">

        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                {{ __('New Expense') }}
            </h2>
            <flux:button href="{{ route('expenses.index') }}" wire:navigate.persist>{{ __('Back') }}</flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex flex-col gap-6">

                <flux:radio.group wire:model.live="expenseType" :label="__('Expense Type')" variant="segmented">
                    <flux:radio value="single" :label="__('Single')" />
                    <flux:radio value="recurring" :label="__('Recurring')" />
                    <flux:radio value="installment" :label="__('Installment')" />
                </flux:radio.group>

                <flux:input wire:model="description" :label="__('Description')"
                    :placeholder="__('Ex: Grocery Shopping')" autofocus />

                <div x-show="expenseType === 'single' || expenseType === 'recurring'">
                    <flux:input wire:model="amount"
                        label="{{ __('Value') }} ({{ auth()->user()->currency_symbol ?? '$' }})" type="number"
                        step="0.01" placeholder="150.00" />
                </div>

                <div x-show="expenseType === 'installment'">
                    <flux:input wire:model="total_amount"
                        label="{{ __('Total Value') }} ({{ auth()->user()->currency_symbol ?? '$' }})" type="number"
                        step="0.01" placeholder="1200.00" />
                </div>

                <flux:input wire:model="date" :label="__('Purchase Date / Start')" type="date" />

                <div x-show="expenseType === 'recurring'">
                    <flux:input wire:model="months" :label="__('Duration (months)')" type="number" placeholder="12" />
                </div>

                <div x-show="expenseType === 'installment'">
                    <flux:input wire:model="installments" :label="__('Number of Installments')" type="number"
                        placeholder="10" />
                </div>

                <div class="grid gap-2">
                    <label for="category_id"
                        class="font-medium text-sm text-zinc-700 dark:text-zinc-200">{{ __('Category') }}</label>
                    <flux:select wire:model="category_id" id="category_id">
                        <option value="" disabled>{{ __('Select a category') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <div class="flex justify-between pt-4">
                    <flux:button href="{{ route('expenses.index') }}" wire:navigate.persist>
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ __('Save Expense') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
