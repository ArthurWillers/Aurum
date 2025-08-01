<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
    {{-- Cards de Resumo Mensal --}}
    <div class="grid gap-4 md:grid-cols-3">
        {{-- Card Receitas --}}
        <x-dashboard-card title="Receitas do Mês" value="R$ {{ number_format($totalIncomes, 2, ',', '.') }}"
            icon="arrow-trending-up" color="green" />

        {{-- Card Despesas --}}
        <x-dashboard-card title="Despesas do Mês" value="R$ {{ number_format($totalExpenses, 2, ',', '.') }}"
            icon="arrow-trending-down" color="red" />

        {{-- Card Saldo --}}
        <x-dashboard-card title="Saldo do Mês" value="R$ {{ number_format($balance, 2, ',', '.') }}" icon="scale"
            color="zinc" :value-color="$balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'" />
    </div>

    {{-- Placeholder para gráficos futuros --}}
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="flex h-full items-center justify-center">
            <p class="text-neutral-500 dark:text-neutral-400">Gráficos serão adicionados em breve...</p>
        </div>
    </div>
</div>
