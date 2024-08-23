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
        Schema::create('modalidades', function(Blueprint $table){
            $table->id();
            $table->string('foto_modalidade')->nullable();
            $table->string('nome_modalidade');
            $table->longText('descricao_modalidade');
            $table->timestamps();
            
           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalidades');
    }
};
