<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clases', function (Blueprint $table) {
            $table->string('clave')->unique()->after('id');
            $table->string('nombre')->after('clave');
            $table->text('descripcion')->nullable()->after('nombre');
            $table->enum('estado', ['en curso', 'finalizada'])->default('en curso')->after('fecha_fin');
        });
    }

    public function down(): void
    {
        Schema::table('clases', function (Blueprint $table) {
            $table->dropColumn(['clave', 'nombre', 'descripcion', 'estado']);
            $table->dropTimestamps(); // <-- Esto elimina created_at y updated_at
        });
    }
};