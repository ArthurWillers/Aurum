<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Dashboard') }}</h2>
    </div>

    {{-- Cards de Resumo Mensal --}}
    <div class="grid gap-3 md:grid-cols-3">
        <x-dashboard-card :title="__('Monthly Income')"
            value="{{ auth()->user()->currency_symbol ?? '$' }} {{ number_format($totalMonthlyIncomes, 2, ',', '.') }}"
            icon="arrow-trending-up" color="green" />
        <x-dashboard-card :title="__('Monthly Expenses')"
            value="{{ auth()->user()->currency_symbol ?? '$' }} {{ number_format($totalMonthlyExpenses, 2, ',', '.') }}"
            icon="arrow-trending-down" color="red" />
        <x-dashboard-card :title="__('Monthly Balance')"
            value="{{ auth()->user()->currency_symbol ?? '$' }} {{ number_format($monthlyBalance, 2, ',', '.') }}"
            icon="scale" color="zinc" :value-color="$monthlyBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'" />
    </div>

    {{-- Cards do Pódio Financeiro --}}
    <div class="grid gap-4 md:grid-cols-2">
        <x-podium-card :title="__('Top 3 Expenses')" icon="arrow-trending-down" color="red" :items="$topExpenses['items']"
            :total="$topExpenses['total']" />
        <x-podium-card :title="__('Top 3 Incomes')" icon="arrow-trending-up" color="green" :items="$topIncomes['items']" :total="$topIncomes['total']" />
    </div>

</div>
