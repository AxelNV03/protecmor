<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoPublicController extends Controller
{
    /**
     * Muestra la vista pública de los productos visibles.
     */
    public function index(Request $request)
    {
        $query = Producto::where('visible', true)
                         ->where('disponible', true); // Solo mostrar los que están disponibles

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Ordenamiento
        if ($request->filled('ordenar_precio')) {
            $query->orderBy('precio', $request->ordenar_precio === 'asc' ? 'asc' : 'desc');
        } else {
             $query->orderBy('nombre', 'asc');
        }

        $productos = $query->paginate(12);
        $categorias = Producto::getCategorias();

        return view('productosPublic', compact('productos', 'categorias'));
    }
}