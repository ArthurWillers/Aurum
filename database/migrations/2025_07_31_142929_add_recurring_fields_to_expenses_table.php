<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Para agrupar as parcelas de uma mesma compra.
            // Será NULL para despesas únicas e para assinaturas.
            $table->uuid('recurring_id')->nullable()->after('user_id');

            // Para identificar o número da parcela (ex: "1/12", "2/12").
            // Será NULL para despesas únicas e para assinaturas.
            $table->string('installment')->nullable()->after('recurring_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['recurring_id', 'installment']);
        });
    }
};