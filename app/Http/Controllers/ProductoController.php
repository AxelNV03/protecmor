<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
// 🚩 CRÍTICO: Importar el Form Request corregido
use App\Http\Requests\SaveProductoRequest; 

class ProductoController extends Controller
{
    /**
     * Mostrar la vista de gestión de productos (index).
     * Esta vista cargará los datos de forma asíncrona.
     */
    public function index()
    {
        // Solo devuelve la vista, los datos se cargan vía AJAX
        return view('admin.parts.productos');
    }

    /**
     * Obtener los productos en formato JSON (usado por Alpine.js para la carga inicial y paginación).
     */
    public function data(Request $request)
    {
        $query = Producto::query();

        // Lógica de filtrado y ordenación
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('categoria', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
        }
        
        if ($request->has('ordenar_por')) {
            $orden = $request->ordenar_por;
            // 🚩 NOTA: Aquí tu modelo usa 'precio' pero tu consulta usa 'precio_venta'. 
            // Si tu columna es 'precio', DEBES usar 'precio' aquí también. Asumo que tu tabla tiene 'precio'.
            if ($orden === 'precio') {
                $query->orderBy('precio', 'asc'); 
            } elseif ($orden === 'categoria') {
                $query->orderBy('categoria', 'asc');
            }
        }
        
        $productos = $query->paginate(12);

        return response()->json(['productos' => $productos]);
    }

    //---------------------------------------------------------

    /**
     * Guardar un nuevo producto. (Usa SaveProductoRequest)
     */
    // 🚩 Usamos el Request corregido para la validación automática
    public function store(SaveProductoRequest $request) 
    {
        $validated = $request->validated(); 
        
        // 🚩 CRÍTICO: Convertir el string '1' o '0' a booleano.
        $validated['disponible'] = (bool)$validated['disponible']; 
        
        // El FormRequest ya validó que el archivo sea una imagen
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        
        $producto = Producto::create($validated);
        // Respuesta HTTP 201 (Created)
        return response()->json(['producto' => $producto, 'message' => 'Producto creado con éxito.'], 201);
    }

    /**
     * Mostrar información detallada de un producto (por ID).
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto);
    }

    /**
     * Actualizar un producto existente.
     */
    // 🚩 Usamos el Request corregido para la validación automática
    public function update(SaveProductoRequest $request, $id) 
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validated();
        
        // 🚩 CRÍTICO: Convertir el string '1' o '0' a booleano.
        $validated['disponible'] = (bool)$validated['disponible']; 

        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        
        $producto->update($validated);
        return response()->json(['producto' => $producto, 'message' => 'Producto actualizado con éxito.']);
    }

    /**
     * Eliminar un producto.
     */
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();
        return response()->json(['message' => 'Producto eliminado con éxito.']);
    }
}