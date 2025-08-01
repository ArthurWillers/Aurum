<div>
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Currency Settings')" :subheading="__('Choose your preferred currency symbol')">
        <form wire:submit="save" class="space-y-4">
            <flux:field>
                <flux:label>{{ __('Currency Symbol') }}</flux:label>
                <flux:select wire:model="currency_symbol" placeholder="{{ __('Select currency symbol') }}">
                    <option value="$">$ ({{ __('Dollar') }})</option>
                    <option value="R$">R$ ({{ __('Real') }})</option>
                </flux:select>
            </flux:field>

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>

                <x-action-message class="me-3" on="currency-updated">
                    {{ __('Currency updated successfully.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</div>
