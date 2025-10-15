<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\SaveProductoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProductoController extends Controller
{
    /**
     * Devuelve la lista de productos como JSON para la carga asíncrona de Alpine.js.
     */
    public function data(Request $request)
    {
        // Retorna todos los productos para la gestión del administrador
        $productos = Producto::latest()->get(); 

        return response()->json($productos);
    }
    
    /**
     * Este método solo redirige o se mantiene vacío, ya que la vista principal
     * se carga por el dashboard con Alpine.js.
     */
    public function index()
    {
        return view('admin.parts.productos'); // la ruta de tu blade
    }

    /**
     * Almacena un nuevo producto.
     */
    public function store(SaveProductoRequest $request)
    {
        $data = $request->validated();

        // Manejo de la subida de la imagen
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('public/productos');
            $data['imagen'] = Storage::url($data['imagen']); // Obtiene la URL pública
        }

        Producto::create($data);

        // Redirige al dashboard con el estado activo para la pestaña 'productos'
        return redirect()->route('admin.dashboard', ['tab' => 'productos'])->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Actualiza el producto especificado.
     */
    public function update(SaveProductoRequest $request, Producto $producto)
    {
        $data = $request->validated();

        // Manejo de la actualización de la imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                $path = str_replace('/storage', 'public', $producto->imagen);
                Storage::delete($path);
            }

            $data['imagen'] = $request->file('imagen')->store('public/productos');
            $data['imagen'] = Storage::url($data['imagen']);
        }

        $producto->update($data);

        return redirect()->route('admin.dashboard', ['tab' => 'productos'])->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Elimina el producto especificado.
     */
    public function destroy(Producto $producto)
    {
        // Eliminar imagen del almacenamiento
        if ($producto->imagen) {
            $path = str_replace('/storage', 'public', $producto->imagen);
            Storage::delete($path);
        }

        $producto->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'productos'])->with('success', 'Producto eliminado exitosamente.');
    }
}