<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Aurum')]
class CurrencySettings extends Component
{
    // Removido: currency_symbol, tudo será R$

    public function mount()
    {
        // Removido: currency_symbol, tudo será R$
    }

    public function save()
    {
        // Removido: currency_symbol, tudo será R$
    }

    public function render()
    {
        return view('livewire.settings.currency-settings');
    }
}
