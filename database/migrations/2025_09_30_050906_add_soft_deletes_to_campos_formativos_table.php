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
            $table->softDeletes(); // <-- Esta línea añade la columna 'deleted_at'
        });
    }

    public function down(): void
    {
        Schema::table('campos_formativos', function (Blueprint $table) {
            $table->dropSoftDeletes(); // <-- Esto permite revertir la migración
        });
    }
};
