<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->enum('tipo', [
                'Talleres prácticos',
                'Diplomados/cursos',
                'Simulacros',
                'Seminarios/conferencias',
                'Campañas comunitarias'
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('tipo')->change();
        });
    }
};
