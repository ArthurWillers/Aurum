<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    {{-- Cards de Resumo Mensal --}}
    <div class="grid gap-3 md:grid-cols-3">
        {{-- Card Receitas --}}
        <x-dashboard-card :title="__('Monthly Income')"
            value="{{ auth()->user()->currency_symbol ?? '$' }} {{ number_format($totalIncomes, 2, ',', '.') }}"
            icon="arrow-trending-up" color="green" />

        {{-- Card Despesas --}}
        <x-dashboard-card :title="__('Monthly Expenses')"
            value="{{ auth()->user()->currency_symbol ?? '$' }} {{ number_format($totalExpenses, 2, ',', '.') }}"
            icon="arrow-trending-down" color="red" />

        {{-- Card Saldo --}}
        <x-dashboard-card :title="__('Monthly Balance')"
            value="{{ auth()->user()->currency_symbol ?? '$' }} {{ number_format($balance, 2, ',', '.') }}" icon="scale"
            color="zinc" :value-color="$balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'" />
    </div>

    {{-- Linha 2: Gráficos de Pizza - Despesas e Receitas por Categoria --}}
    <div class="grid gap-4 lg:grid-cols-2 flex-1 min-h-0">
        {{-- Gráfico de Pizza: Despesas por Categoria --}}
        <div
            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-4">
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                    {{ __('Expenses by Category') }}</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Current month breakdown') }}</p>
            </div>
            <div class="flex h-40 items-center justify-center">
                <p class="text-neutral-500 dark:text-neutral-400">{{ __('Pie chart will be added soon...') }}</p>
            </div>
        </div>

        {{-- Gráfico de Pizza: Receitas por Categoria --}}
        <div
            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-4">
            <div class="mb-3">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ __('Income by Category') }}
                </h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Current month breakdown') }}</p>
            </div>
            <div class="flex h-40 items-center justify-center">
                <p class="text-neutral-500 dark:text-neutral-400">{{ __('Pie chart will be added soon...') }}</p>
            </div>
        </div>
    </div>

    {{-- Linha 3: Gráfico de Área - Evolução de Receitas, Despesas e Saldo --}}
    <div
        class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-4 flex-1 min-h-0">
        <div class="mb-3">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ __('Financial Evolution') }}
            </h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                {{ __('Income, expenses and balance over time') }}</p>
        </div>
        <div class="flex h-48 items-center justify-center">
            <p class="text-neutral-500 dark:text-neutral-400">{{ __('Area chart will be added soon...') }}</p>
        </div>
    </div>
</div>
