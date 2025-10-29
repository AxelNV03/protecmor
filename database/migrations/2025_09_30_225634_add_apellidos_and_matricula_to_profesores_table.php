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
        Schema::table('profesores', function (Blueprint $table) {
            $table->string('apeP')->after('user_id');
            $table->string('apeM')->after('apeP');
            $table->string('matricula')->unique()->nullable()->after('apeM');
            $table->date('fecha_nacimiento')->after('matricula');
            $table->text('direccion')->after('fecha_nacimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profesores', function (Blueprint $table) {
            $table->dropColumn(['apeP', 'apeM', 'matricula', 'fecha_nacimiento', 'direccion']);
        });
    }
};