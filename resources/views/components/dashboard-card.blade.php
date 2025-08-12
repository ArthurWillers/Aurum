@props(['title', 'value', 'icon', 'color' => 'zinc', 'valueColor' => null, 'href' => null])

@php
    $colorClasses = [
        'green' => [
            'bg' => 'bg-green-100 dark:bg-green-900/20',
            'icon' => 'text-green-600 dark:text-green-400',
            'value' => 'text-green-600 dark:text-green-400',
        ],
        'red' => [
            'bg' => 'bg-red-100 dark:bg-red-900/20',
            'icon' => 'text-red-600 dark:text-red-400',
            'value' => 'text-red-600 dark:text-red-400',
        ],
        'zinc' => [
            'bg' => 'bg-zinc-100 dark:bg-zinc-700',
            'icon' => 'text-zinc-600 dark:text-zinc-400',
            'value' => 'text-zinc-900 dark:text-zinc-100',
        ],
    ];

    $classes = $colorClasses[$color] ?? $colorClasses['zinc'];
    $valueColorClass = $valueColor ?? $classes['value'];
    
    // Classes base do card
    $cardClasses = "relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-6";
    
    // Adiciona classes para interatividade se for um link
    if ($href) {
        $cardClasses .= " transition-all duration-200 hover:border-neutral-300 dark:hover:border-neutral-600 hover:shadow-md cursor-pointer";
    }
@endphp

@if($href)
    <a href="{{ $href }}" wire:navigate.persist class="{{ $cardClasses }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ $title }}</p>
                <p class="text-2xl font-bold {{ $valueColorClass }}">
                    {{ $value }}
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $classes['bg'] }}">
                <flux:icon name="{{ $icon }}" class="h-6 w-6 {{ $classes['icon'] }}" />
            </div>
        </div>
    </a>
@else
    <div class="{{ $cardClasses }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ $title }}</p>
                <p class="text-2xl font-bold {{ $valueColorClass }}">
                    {{ $value }}
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $classes['bg'] }}">
                <flux:icon name="{{ $icon }}" class="h-6 w-6 {{ $classes['icon'] }}" />
            </div>
        </div>
    </div>
@endif
