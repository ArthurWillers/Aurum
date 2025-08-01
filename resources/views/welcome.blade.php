<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="bg-slate-900 text-gray-300 antialiased">

    <div class="flex min-h-screen flex-col">

        <header class="w-full">
            <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
                {{-- Logo --}}
                <div class="flex lg:flex-1">
                    <a href="{{ route('home') }}" class="-m-1.5 flex items-center gap-3 p-1.5">
                        <x-app-logo-icon class="h-8 w-auto text-[#DAA520]" />
                        <span class="text-xl font-semibold text-white">{{ config('app.name', 'Aurum') }}</span>
                    </a>
                </div>

                <div class="flex flex-1 justify-end gap-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="flex items-center justify-center rounded-md bg-[#DAA520] px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-[#c5a435]">
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold leading-6 text-zinc-100 ring-1 ring-white/20 transition hover:bg-white/10 hover:text-white">
                            {{ __('Log in') }}
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex items-center justify-center rounded-md bg-[#DAA520] px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-[#c5a435]">
                            {{ __('Create Account') }}
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <main class="flex-grow">
            <div class="relative mx-auto max-w-7xl px-6 py-20 sm:py-24 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-x-16 gap-y-12 lg:grid-cols-2">

                    {{-- Coluna Esquerda --}}
                    <div class="text-left">
                        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                            {{ __('Personal Financial Manager') }}
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-300 text-justify">
                            Aurum is free software under GPLv3 license, for personal finance management. The system
                            allows the user to register and track their income and expense transactions, as well as
                            organize them into customizable categories for better analysis and financial control. The
                            application was built using modern technologies like <strong
                                class="font-semibold text-white">Laravel</strong>, <strong
                                class="font-semibold text-white">Livewire</strong> and
                            <strong class="font-semibold text-white">TailwindCSS</strong>.
                        </p>
                    </div>

                    {{-- Coluna Direita --}}
                    <div class="rounded-2xl bg-slate-950/50 p-8 ring-1 ring-white/10">
                        <h3 class="text-lg font-semibold text-white mb-6">Funcionalidades Implementadas</h3>
                        <dl class="flex flex-col gap-y-6">
                            {{-- Feature 1 --}}
                            <div class="flex gap-x-4">
                                <dt class="flex-none">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800">
                                        <svg class="h-5 w-5 text-[#DAA520]" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </dt>
                                <dd class="text-base leading-7 text-gray-300">
                                    <strong class="font-semibold text-white">{{ __('Intuitive Dashboard') }}:</strong>
                                    {{ __('Dashboard overview of balance, income and expenses with charts for financial health analysis.') }}
                                </dd>
                            </div>
                            {{-- Feature 2 --}}
                            <div class="flex gap-x-4">
                                <dt class="flex-none">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800">
                                        <svg class="h-5 w-5 text-[#DAA520]" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                        </svg>
                                    </div>
                                </dt>
                                <dd class="text-base leading-7 text-gray-300">
                                    <strong
                                        class="font-semibold text-white">{{ __('Transaction Management') }}:</strong>
                                    {{ __('Interface to add, edit and view all financial movements in an organized way.') }}
                                </dd>
                            </div>
                            {{-- Feature 3 --}}
                            <div class="flex gap-x-4">
                                <dt class="flex-none">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800">
                                        <svg class="h-5 w-5 text-[#DAA520]" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 6h.008v.008H6V6z" />
                                        </svg>
                                    </div>
                                </dt>
                                <dd class="text-base leading-7 text-gray-300">
                                    <strong class="font-semibold text-white">{{ __('Flexible Categories') }}:</strong>
                                    {{ __('System to create and manage categories with custom colors, making it easier to identify expenses.') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="w-full shrink-0">
            <div
                class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-6 py-6 sm:flex-row lg:px-8">
                <div class="text-sm text-zinc-500">
                    Desenvolvido por <a href="https://github.com/ArthurWillers" target="_blank"
                        class="font-medium text-zinc-400 hover:text-[#DAA520]">Arthur Willers</a>
                </div>
                <div class="flex items-center gap-x-6">
                    <a href="https://github.com/ArthurWillers/Aurum" target="_blank"
                        class="flex items-center gap-x-2 text-sm text-zinc-500 hover:text-zinc-400">
                        Ver o código-fonte no GitHub
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.168 6.839 9.492.5.092.682-.217.682-.482 0-.237-.009-.868-.014-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.031-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.378.203 2.398.1 2.65.64.7 1.028 1.595 1.028 2.688 0 3.848-2.338 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.001 10.001 0 0022 12c0-5.523-4.477-10-10-10z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </footer>

    </div>

</body>

</html>
