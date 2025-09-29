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
        Schema::table('campos_formativos', function (Blueprint $table) {
            // Añade la nueva columna 'descripcion' de tipo TEXT,
            // que puede ser nula, después de la columna 'tipo'.
            $table->text('descripcion')->nullable()->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campos_formativos', function (Blueprint $table) {
            // Esto permite revertir el cambio si es necesario.
            $table->dropColumn('descripcion');
        });
    }
};