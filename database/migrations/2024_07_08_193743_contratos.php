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
        Schema::create('contratos', function(Blueprint $table){
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('planos_id')->constrained('planos')->onDelete('cascade');
            $table->date('data_inicio');
            $table->date('data_renovacao');
            $table->date('data_vencimento');
            $table->decimal('valor_plano',8,2);
            $table->decimal('desconto',5,2);
            $table->integer('parcelas');
            $table->longText('observacoes');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
