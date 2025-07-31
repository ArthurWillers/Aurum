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
        // Altera a tabela de despesas (expenses)
        Schema::table('expenses', function (Blueprint $table) {
            // Remove a chave estrangeira antiga
            $table->dropForeign(['category_id']);

            // Adiciona a nova chave estrangeira com restrição de exclusão
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->restrictOnDelete();
        });

        // Altera a tabela de receitas (incomes)
        Schema::table('incomes', function (Blueprint $table) {
            // Remove a chave estrangeira antiga
            $table->dropForeign(['category_id']);

            // Adiciona a nova chave estrangeira com restrição de exclusão
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });
    }
};
