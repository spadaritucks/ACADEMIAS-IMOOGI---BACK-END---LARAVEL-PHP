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
        Schema::create('pagamentos_mensais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained()->onDelete('cascade');
            $table->string('comprovante');
            $table->decimal('valor_pago', 8, 2);
            $table->date('data_pagamento');
            $table->timestamps();
        });

        Schema::table('contratos', function (Blueprint $table) {
            $table->string('comprovante')->nullable()->after('data_vencimento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pagamentos_mensais');
    }
};
