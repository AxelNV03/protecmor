<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Producto; // Importa el modelo para acceder a getCategorias()

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Se debe usar la sentencia raw() o enum() si su base de datos lo soporta
        // Asegúrate de que las categorías coincidan con las de tu modelo
        $categorias = Producto::getCategorias();

        Schema::table('productos', function (Blueprint $table) use ($categorias) {
            // Eliminar la columna 'categoria' existente
            $table->dropColumn('categoria');
        });

        Schema::table('productos', function (Blueprint $table) use ($categorias) {
            // Agregar la nueva columna 'categoria' como ENUM con los valores definidos
            // Se usa after('proveedor') para mantener el orden
            $table->enum('categoria', $categorias)->after('proveedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si revierte la migración, se regresa a un tipo STRING genérico
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
        
        Schema::table('productos', function (Blueprint $table) {
            $table->string('categoria')->after('proveedor');
        });
    }
};