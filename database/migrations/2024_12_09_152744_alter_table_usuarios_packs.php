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
        Schema::table('usuarios_packs', function (Blueprint $table) {
            $table->foreignId('packs_id')->constrained('packs')->onDelete('cascade');
            $table->dropForeign(['pack_id']); // Corrigido para usar um array
            $table->dropColumn('pack_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios_packs', function (Blueprint $table) {
            //
        });
    }
};
