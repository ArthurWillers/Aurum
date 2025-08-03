<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="dashboardCharts">

    {{-- Cabeçalho --}}
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Dashboard') }}</h2>
        <div class="flex flex-col items-end gap-1">
            <button onclick="window.location.reload()"
                class="cursor-pointer flex items-center gap-2 px-3 py-2 text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors"
                title="{{ __('Reload charts') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                {{ __('Reload charts') }}
            </button>
        </div>
    </div>

    {{-- Cards de Resumo Mensal --}}
    <div class="grid gap-3 md:grid-cols-3">
        <x-dashboard-card :title="__('Monthly Income')" value="R$ {{ number_format($totalMonthlyIncomes, 2, ',', '.') }}"
            icon="arrow-trending-up" color="green" />
        <x-dashboard-card :title="__('Monthly Expenses')" value="R$ {{ number_format($totalMonthlyExpenses, 2, ',', '.') }}"
            icon="arrow-trending-down" color="red" />
        <x-dashboard-card :title="__('Monthly Balance')" value="R$ {{ number_format($monthlyBalance, 2, ',', '.') }}" icon="scale"
            color="zinc" :value-color="$monthlyBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'" />
    </div>

    {{-- Gráficos de Pizza --}}
    <div class="grid gap-4 lg:grid-cols-2 flex-1 min-h-0">
        <div
            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-4">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ __('Expenses by Category') }}
            </h3>
            <div wire:ignore x-ref="expensesPieChart" class="h-80"></div>
        </div>
        <div
            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-4">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ __('Income by Category') }}</h3>
            <div wire:ignore x-ref="incomesPieChart" class="h-80"></div>
        </div>
    </div>

    {{-- Gráfico de Evolução Financeira --}}
    <div
        class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-4 flex-1 min-h-0">
        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ __('Financial Evolution') }}</h3>
        <div wire:ignore x-ref="evolutionChart" class="h-80"></div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardCharts', () => ({
            expensesPieChart: null,
            incomesPieChart: null,
            evolutionChart: null,

            init() {
                this.$nextTick(() => {
                    console.log('Dashboard charts component initialized');
                    const initialData = {
                        expensesByCategory: @js($expensesByCategory),
                        incomesByCategory: @js($incomesByCategory),
                        financialEvolution: @js($financialEvolution)
                    };
                    console.log('Initial data:', initialData);
                    this.initializeCharts(initialData);
                });
            },

            initializeCharts(data) {
                console.log('Initializing charts with data:', data);
                if (!data) return;

                this.updateExpensesPieChart(data);
                this.updateIncomesPieChart(data);
                this.updateEvolutionChart(data);
            },

            getBaseOptions() {
                const isDark = document.documentElement.classList.contains('dark');
                return {
                    chart: {
                        background: 'transparent',
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    theme: {
                        mode: isDark ? 'dark' : 'light'
                    },
                    tooltip: {
                        theme: isDark ? 'dark' : 'light'
                    }
                };
            },

            getPieChartOptions(baseOptions, color) {
                return {
                    chart: {
                        ...baseOptions.chart,
                        type: 'pie',
                        width: '100%',
                        height: '100%'
                    },
                    theme: {
                        ...baseOptions.theme,
                        monochrome: {
                            enabled: true,
                            color: color,
                            shadeTo: 'light',
                            shadeIntensity: 0.65
                        }
                    },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                offset: -5
                            }
                        }
                    },
                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            left: 0,
                            right: 0
                        }
                    },
                    dataLabels: {
                        formatter(val, opts) {
                            const name = opts.w.globals.labels[opts.seriesIndex];
                            return [name, val.toFixed(1) + '%'];
                        },
                        style: {
                            colors: ['#ffffff']
                        }
                    },
                    legend: {
                        show: false
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => `R$ ${val.toLocaleString('pt-BR', { 
                                minimumFractionDigits: 2, 
                                maximumFractionDigits: 2 
                            })}`
                        }
                    }
                };
            },

            updateExpensesPieChart(data) {
                if (this.expensesPieChart) {
                    this.expensesPieChart.destroy();
                    this.expensesPieChart = null;
                }

                const labels = Object.keys(data.expensesByCategory || {});
                const values = Object.values(data.expensesByCategory || {});

                if (values.length > 0) {
                    const options = {
                        ...this.getBaseOptions(),
                        ...this.getPieChartOptions(this.getBaseOptions(), '#ef4444'),
                        series: values,
                        labels: labels
                    };

                    this.expensesPieChart = new ApexCharts(this.$refs.expensesPieChart, options);
                    this.expensesPieChart.render();
                } else {
                    this.showEmptyMessage(this.$refs.expensesPieChart,
                        'Nenhuma despesa registrada este mês.');
                }
            },

            updateIncomesPieChart(data) {
                if (this.incomesPieChart) {
                    this.incomesPieChart.destroy();
                    this.incomesPieChart = null;
                }

                const labels = Object.keys(data.incomesByCategory || {});
                const values = Object.values(data.incomesByCategory || {});

                if (values.length > 0) {
                    const options = {
                        ...this.getBaseOptions(),
                        ...this.getPieChartOptions(this.getBaseOptions(), '#22c55e'),
                        series: values,
                        labels: labels
                    };

                    this.incomesPieChart = new ApexCharts(this.$refs.incomesPieChart, options);
                    this.incomesPieChart.render();
                } else {
                    this.showEmptyMessage(this.$refs.incomesPieChart,
                        'Nenhuma receita registrada este mês.');
                }
            },

            updateEvolutionChart(data) {
                if (this.evolutionChart) {
                    this.evolutionChart.destroy();
                    this.evolutionChart = null;
                }

                if (data.financialEvolution?.labels?.length > 0) {
                    const baseOptions = this.getBaseOptions();
                    const isDark = document.documentElement.classList.contains('dark');
                    const balanceColor = isDark ? '#94a3b8' : '#475569';

                    const options = {
                        ...baseOptions,
                        chart: {
                            ...baseOptions.chart,
                            type: 'area',
                            height: 320
                        },
                        series: [{
                                name: 'Receita',
                                data: data.financialEvolution.incomes || [],
                                color: '#22c55e'
                            },
                            {
                                name: 'Despesa',
                                data: data.financialEvolution.expenses || [],
                                color: '#ef4444'
                            },
                            {
                                name: 'Saldo',
                                data: data.financialEvolution.balance || [],
                                color: balanceColor
                            }
                        ],
                        xaxis: {
                            categories: data.financialEvolution.labels
                        },
                        stroke: {
                            curve: 'monotoneCubic',
                            width: 2
                        },
                        dataLabels: {
                            enabled: false
                        },
                        grid: {
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            strokeDashArray: 4
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                colors: isDark ? '#d1d5db' : '#374151'
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: (val) => `R$ ${val !== undefined ? 
                                    val.toLocaleString('pt-BR', { 
                                        minimumFractionDigits: 2, 
                                        maximumFractionDigits: 2 
                                    }) : '0,00'}`
                            }
                        }
                    };

                    this.evolutionChart = new ApexCharts(this.$refs.evolutionChart, options);
                    this.evolutionChart.render();
                } else {
                    this.showEmptyMessage(this.$refs.evolutionChart,
                        'Carregando evolução financeira...');
                }
            },

            showEmptyMessage(element, message) {
                element.innerHTML = `
                    <div class="flex h-full items-center justify-center">
                        <p class="text-neutral-500 dark:text-neutral-400">${message}</p>
                    </div>
                `;
            }
        }))
    })
</script>
