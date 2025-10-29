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
        Schema::table('alumnos', function (Blueprint $table) {
            // Añade la columna para el Apellido Paterno después de user_id
            $table->string('apeP')->after('user_id');
            
            // Añade la columna para el Apellido Materno después de apeP
            $table->string('apeM')->after('apeP');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            // Define cómo revertir los cambios
            $table->dropColumn(['apeP', 'apeM']);
        });
    }
};