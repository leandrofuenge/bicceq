<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Vendedor
            $table->string('numero_venda')->unique(); // Número único da venda
            $table->decimal('total', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('valor_pago', 10, 2);
            $table->decimal('troco', 10, 2)->default(0);
            $table->enum('status', ['pendente', 'concluida', 'cancelada'])->default('concluida');
            $table->enum('forma_pagamento', ['dinheiro', 'cartao', 'pix', 'transferencia'])->default('dinheiro');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendas');
    }
};