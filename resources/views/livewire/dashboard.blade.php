<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
    {{-- Cards de Resumo Mensal --}}
    <div class="grid gap-4 md:grid-cols-3">
        {{-- Card Receitas --}}
        <div
            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Receitas do Mês</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        R$ {{ number_format($totalIncomes, 2, ',', '.') }}
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                    <flux:icon.arrow-trending-up class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
            </div>
        </div>

        {{-- Card Despesas --}}
        <div
            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Despesas do Mês</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        R$ {{ number_format($totalExpenses, 2, ',', '.') }}
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/20">
                    <flux:icon.arrow-trending-down class="h-6 w-6 text-red-600 dark:text-red-400" />
                </div>
            </div>
        </div>

        {{-- Card Saldo --}}
        <div
            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Saldo do Mês</p>
                    <p
                        class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        R$ {{ number_format($balance, 2, ',', '.') }}
                    </p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-700">
                    <flux:icon.scale class="h-6 w-6 text-zinc-600 dark:text-zinc-400" />
                </div>
            </div>
        </div>
    </div>

    {{-- Placeholder para gráficos futuros --}}
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="flex h-full items-center justify-center">
            <p class="text-neutral-500 dark:text-neutral-400">Gráficos serão adicionados em breve...</p>
        </div>
    </div>
</div>
