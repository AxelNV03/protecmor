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
        Schema::table('materiales', function (Blueprint $table) {
            // Añade la columna 'descripcion' de tipo TEXT, que puede ser nula,
            // después de la columna 'titulo'.
            $table->text('descripcion')->nullable()->after('titulo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materiales', function (Blueprint $table) {
            // Permite revertir el cambio.
            $table->dropColumn('descripcion');
        });
    }
};