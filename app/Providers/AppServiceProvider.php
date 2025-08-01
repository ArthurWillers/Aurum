<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ativa a prevenção de lazy loading somente fora do ambiente de produção
        Model::preventLazyLoading(! app()->isProduction());

        // Registra helper global para formatação de moeda
        if (! function_exists('formatCurrency')) {
            function formatCurrency($amount)
            {
                $symbol = Auth::check() ? Auth::user()->currency_symbol ?? '$' : '$';
                return $symbol . ' ' . number_format($amount, 2, ',', '.');
            }
        }

        // Registra directive Blade para formatação de moeda
        Blade::directive('currency', function ($amount) {
            return "<?php echo formatCurrency($amount); ?>";
        });
    }
}
