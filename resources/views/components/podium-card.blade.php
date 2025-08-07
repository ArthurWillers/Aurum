@props(['title', 'icon', 'color' => 'red', 'items' => [], 'total' => 0])

@php
    $colorClasses = [
        'green' => [
            'bg' => 'bg-green-100 dark:bg-green-900/20',
            'icon' => 'text-green-600 dark:text-green-400',
            'ranking' => 'text-green-600 dark:text-green-400',
            'bar' => 'bg-green-500 dark:bg-green-400',
            'bar_bg' => 'bg-green-100 dark:bg-green-900/20',
        ],
        'red' => [
            'bg' => 'bg-red-100 dark:bg-red-900/20',
            'icon' => 'text-red-600 dark:text-red-400',
            'ranking' => 'text-red-600 dark:text-red-400',
            'bar' => 'bg-red-500 dark:bg-red-400',
            'bar_bg' => 'bg-red-100 dark:bg-red-900/20',
        ],
    ];

    $classes = $colorClasses[$color] ?? $colorClasses['red'];
@endphp

<div
    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6">

    {{-- Header do Card --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $classes['bg'] }}">
                <flux:icon name="{{ $icon }}" class="h-6 w-6 {{ $classes['icon'] }}" />
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ $title }}</h3>
        </div>
    </div>

    {{-- Gráfico de Barras --}}
    <div class="space-y-4">
        @forelse ($items as $index => $item)
            @php
                // Calcula a porcentagem da barra baseada no total geral (receitas ou despesas)
                $currentValue = $item['raw_value'] ?? 0;
                $percentage = $total > 0 ? ($currentValue / $total) * 100 : 0;
                $minHeight = 2; // Altura mínima em porcentagem para barras pequenas serem visíveis
                $barHeight = max($minHeight, $percentage);
            @endphp

            <div class="flex items-end gap-4 py-2">
                {{-- Ranking --}}
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-700 flex-shrink-0">
                    <span class="text-sm font-bold {{ $classes['ranking'] }}">{{ $index + 1 }}</span>
                </div>

                {{-- Informações e Barra --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100 truncate">
                                {{ $item['name'] }}
                            </p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300 font-medium">
                                {{ number_format($percentage, 1) }}% {{ __('of total') }}
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-neutral-900 dark:text-neutral-100 ml-2">
                            {{ $item['value'] }}
                        </p>
                    </div>

                    {{-- Barra do Gráfico --}}
                    <div class="relative h-3 {{ $classes['bar_bg'] }} rounded-full overflow-hidden">
                        <div class="{{ $classes['bar'] }} h-full rounded-full transition-all duration-700 ease-out"
                            style="width: {{ $barHeight }}%"></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    {{ __('No data available for this month') }}
                </p>
            </div>
        @endforelse
    </div>
</div>
