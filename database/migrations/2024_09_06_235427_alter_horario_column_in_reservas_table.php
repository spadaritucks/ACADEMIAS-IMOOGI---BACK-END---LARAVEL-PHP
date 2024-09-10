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
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('dia_semana')->after('horario'); // Segunda, Terça, etc.
        });
        Schema::table('aulas', function (Blueprint $table) {
            $table->string('dia_semana')->after('horario'); // Segunda, Terça, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            //
        });
    }
};
