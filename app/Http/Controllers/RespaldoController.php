<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Respaldo;


class RespaldoController extends Controller
{
    /**
     * Muestra la lista de respaldos existentes.
     */
    // En RespaldoController.php
    public function data(): \Illuminate\Http\JsonResponse
    {
        // Carga los respaldos con la información del usuario que los creó
        $respaldos = Respaldo::with('usuario')->latest('fecha_creacion')->get();
        return response()->json($respaldos);
    }

    /**
     * Genera un nuevo respaldo de la base de datos.
     */
    public function generar(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre_personalizado' => 'nullable|string|alpha_dash',
        ]);

        $nombreArchivo = $request->input('nombre_personalizado') 
            ? $request->input('nombre_personalizado') . '.zip' 
            : null;

        // Ejecuta el respaldo
        Artisan::call('backup:run', [
            '--only-db' => true,
            '--filename' => $nombreArchivo
        ]);
        
        // --- LÓGICA AÑADIDA ---
        // Obtenemos la información del disco y carpeta desde la configuración del paquete
        $diskName = config('backup.backup.destination.disks')[0];
        $disk = Storage::disk($diskName);
        $folder = config('backup.backup.name');

        // Buscamos el archivo más reciente que se acaba de crear
        $latestFile = collect($disk->allFiles($folder))->last();

        // Guardamos el registro en la nueva tabla 'respaldos'
        if ($latestFile) {
            Respaldo::create([
                'usuario_id' => Auth::id(),
                'nombre_archivo' => basename($latestFile),
                'ruta' => $latestFile,
                'tamano_bytes' => $disk->size($latestFile),
            ]);
        }
        // --- FIN ---

        return back()->with('success', 'Respaldo generado y registrado correctamente.');
    }

    /**
     * Descarga un archivo de respaldo específico.
     */
    public function descargar(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Valida que el path del archivo sea seguro
        $path = $request->validate(['path' => 'required|string'])['path'];
        
        // Descarga el archivo desde el disco de storage
        return Storage::disk('local')->download($path);
    }
}