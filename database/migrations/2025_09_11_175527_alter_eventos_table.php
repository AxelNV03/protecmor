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
        Schema::table('eventos', function (Blueprint $table) {
            // Eliminar las columnas 'requiere_inscripcion' y 'visible'
            $table->dropColumn('requiere_inscripcion');
            $table->dropColumn('visible');

            // Modificar la columna 'publico' para que solo tenga dos opciones
            $table->enum('publico', ['alumnos', 'general'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Revertir los cambios
            $table->enum('publico', ['alumnos', 'general', 'ambos'])->change();
            $table->boolean('visible')->default(true);
            $table->boolean('requiere_inscripcion')->default(false);
        });
    }
};
