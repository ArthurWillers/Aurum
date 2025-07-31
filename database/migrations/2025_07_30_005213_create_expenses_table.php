<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('transaction_group_uuid')->nullable(); // Para agrupar as parcelas
            $table->integer('installment_number')->nullable();   // A parcela atual
            $table->integer('total_installments')->nullable();   // O total de parcelas

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};