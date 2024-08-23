<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('planos', function(Blueprint $table){
            $table->id();
            $table->string('nome_plano');
            $table->integer('duracao');
            $table->decimal('valor_matricula',8,2);
            $table->decimal('valor_mensal',8,2);
            $table->decimal('valor_total',8,2);
            $table->integer('num_modalidades');
            $table->string('status');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};
