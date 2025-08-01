<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Aurum')]
class CurrencySettings extends Component
{
    public $currency_symbol;

    public function mount()
    {
        $this->currency_symbol = Auth::user()->currency_symbol ?? '$';
    }

    public function save()
    {
        Auth::user()->update([
            'currency_symbol' => $this->currency_symbol
        ]);

        $this->dispatch('currency-updated');
    }

    public function render()
    {
        return view('livewire.settings.currency-settings');
    }
}
