@if (session('success') || session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
        style="display: none;"
        class="fixed bottom-6 right-6 z-50 min-w-[300px] max-w-sm flex items-center gap-4 text-zinc-900 text-base font-normal rounded-2xl shadow-2xl px-6 py-4 border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900"
        role="alert" aria-live="assertive">
        <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full"
            :class="{ 'bg-green-100': {{ session('success') ? 'true' : 'false' }}, 'bg-red-100': {{ session('error') ? 'true' : 'false' }} }">
            @if (session('success'))
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            @else
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @endif
        </div>
        <div class="flex-1 text-zinc-900 dark:text-zinc-100">
            {{ session('success') ?? session('error') }}
        </div>
        <button @click="show = false"
            class="ml-2 p-1 rounded-full hover:bg-zinc-200 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-400"
            aria-label="Fechar">
            <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-300" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif
