@props([])

<div x-data="{
    show: false,
    message: '',
    type: 'success',
    timeout: null,
    progressWidth: 100,
    progressInterval: null,

    init() {
        // Função global para ser chamada diretamente
        window.showToast = (data) => {
            this.displayToast(data);
        };

        // Escutar evento do Livewire
        if (typeof Livewire !== 'undefined') {
            Livewire.on('show-toast', (data) => {
                this.displayToast(data[0] || data);
            });
        }

        // Aguardar Livewire carregar se ainda não está disponível
        document.addEventListener('livewire:load', () => {
            Livewire.on('show-toast', (data) => {
                this.displayToast(data[0] || data);
            });
        });
    },

    displayToast(data) {
        this.message = data.message;
        this.type = data.type || 'success';
        this.show = true;
        this.progressWidth = 100;

        // Limpar timeouts anteriores se existirem
        if (this.timeout) clearTimeout(this.timeout);
        if (this.progressInterval) clearInterval(this.progressInterval);

        // Animação da barra de progresso
        this.progressInterval = setInterval(() => {
            this.progressWidth -= 2;
            if (this.progressWidth <= 0) {
                clearInterval(this.progressInterval);
            }
        }, 100);

        // Auto-fechar após 5 segundos
        this.timeout = setTimeout(() => {
            this.hideToast();
        }, 5000);
    },

    hideToast() {
        this.show = false;
        if (this.timeout) clearTimeout(this.timeout);
        if (this.progressInterval) clearInterval(this.progressInterval);
    }
}" x-show="show" x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-full opacity-0" class="fixed top-4 right-4 z-50 max-w-sm w-full"
    style="display: none;">
    <!-- Toast Container -->
    <div
        class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-xl overflow-hidden">
        <!-- Conteúdo Principal -->
        <div class="p-4">
            <div class="flex items-start">
                <!-- Ícone -->
                <div class="flex-shrink-0">
                    <!-- Ícone de Sucesso -->
                    <template x-if="type === 'success'">
                        <div
                            class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-full">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </template>

                    <!-- Ícone de Erro -->
                    <template x-if="type === 'error'">
                        <div
                            class="flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900/20 rounded-full">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </template>
                </div>

                <!-- Mensagem -->
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100" x-text="message"></p>
                </div>

                <!-- Botão Fechar -->
                <div class="ml-4 flex flex-shrink-0">
                    <button @click="hideToast()"
                        class="inline-flex rounded-md text-zinc-400 dark:text-zinc-500 hover:text-zinc-500 dark:hover:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-500 dark:focus:ring-offset-zinc-800">
                        <span class="sr-only">Fechar</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Barra de Progresso -->
        <div class="h-1 bg-zinc-200 dark:bg-zinc-700">
            <div class="h-full transition-all duration-100 ease-linear"
                :class="type === 'success' ? 'bg-green-500 dark:bg-green-400' : 'bg-red-500 dark:bg-red-400'"
                :style="'width: ' + progressWidth + '%'"></div>
        </div>
    </div>
</div>

<!-- Toast Session Handler Script -->
@if (session()->has('toast'))
    <script>
        (function() {
            const sessionToastData = @json(session('toast'));

            function triggerToast() {
                // Método 1: Via função global (mais direto)
                if (typeof window.showToast === 'function') {
                    window.showToast(sessionToastData);
                    return true;
                }

                // Método 2: Via evento Livewire (fallback)
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('show-toast', sessionToastData);
                    return true;
                }

                return false;
            }

            // Tentar imediatamente se a página já carregou
            if (document.readyState === 'complete') {
                setTimeout(triggerToast, 200);
            } else {
                // Aguardar carregamento completo
                window.addEventListener('load', () => {
                    setTimeout(triggerToast, 200);
                });
            }

            // Para navegação SPA do Livewire
            document.addEventListener('livewire:navigated', () => {
                setTimeout(triggerToast, 300);
            });
        })();
    </script>
@endif
