<div class="px-2">
    <label for="month_selector" class="text-xs font-semibold text-zinc-400">{{ __('Reference Month') }}</label>
    <flux:input id="month_selector" wire:model.live="selectedMonth" type="month" class="mt-1" />
</div>
