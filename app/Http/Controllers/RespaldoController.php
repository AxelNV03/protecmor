<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Respaldo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


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

        $nombrePersonalizado = $request->input('nombre_personalizado');
        $nombreArchivoFinal = $nombrePersonalizado ? $nombrePersonalizado . '.zip' : null;

        // 1. Crear el validador manualmente
        $validator = Validator::make(
            ['nombre_final' => $nombreArchivoFinal], // Datos a validar
            ['nombre_final' => ['nullable', 'unique:respaldos,nombre_archivo']] // Reglas
        );

        // 2. Si la validación falla, lanzar una excepción que redirige automáticamente
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        Artisan::call('backup:run', [
            '--only-db' => true,
            '--filename' => $nombreArchivo,
        ]);
        
        // --- LÓGICA CORREGIDA ---
        $diskName = config('backup.backup.destination.disks')[0];
        $disk = Storage::disk($diskName);
        $folder = config('backup.backup.name');
        
        $filePath = '';

        if ($nombreArchivo) {
            // Si hay un nombre personalizado, construimos la ruta directamente.
            $filePath = $folder . '/' . $nombreArchivo;
        } else {
            // Si no, buscamos el archivo más reciente (comportamiento anterior).
            $filePath = collect($disk->allFiles($folder))->last();
        }

        if ($filePath && $disk->exists($filePath)) {
            Respaldo::create([
                'usuario_id' => Auth::id(),
                'nombre_archivo' => basename($filePath),
                'ruta' => $filePath,
                'tamano_bytes' => $disk->size($filePath),
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

    public function restaurar(Request $request, Respaldo $respaldo): RedirectResponse
    {

        // 1. Valida que la contraseña venga en la petición
        $request->validate(['password' => 'required|string']);



        $diskName = config('backup.backup.destination.disks')[0];
        $disk = Storage::disk($diskName);
        $path = $respaldo->ruta;
        $tempDir = null;

            // 2. Comprueba si la contraseña es correcta
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->with('error', 'La contraseña es incorrecta.');
        }

        try {
            if (!$disk->exists($path)) {
                return back()->with('error', 'El archivo de respaldo no se encontró.');
            }

            Artisan::call('down');

            // --- Toda tu lógica de descompresión y restauración va aquí dentro ---
            $tempDir = storage_path('app/backup-temp/restore-' . time());
            mkdir($tempDir, 0755, true);
            
            $zip = new \ZipArchive;
            if ($zip->open($disk->path($path)) === TRUE) {
                $zip->extractTo($tempDir);
                $zip->close();
            } else {
                throw new \Exception('No se pudo abrir el archivo ZIP.');
            }
            
            $sqlFile = collect(File::allFiles($tempDir))
                        ->first(fn($file) => str_ends_with($file->getPathname(), '.sql'));

            if (!$sqlFile) {
                throw new \Exception('No se encontró un archivo .sql válido en el respaldo.');
            }
            
            $dbConfig = config("database.connections.mysql");
            $mysqlPath = $dbConfig['dump']['dump_binary_path'] ?? '';
            $mysqlCommand = empty($mysqlPath) ? 'mysql' : rtrim($mysqlPath, '/\\') . DIRECTORY_SEPARATOR . 'mysql';
            
            $command = sprintf(
                '%s -h %s -u %s %s %s < %s',
                $mysqlCommand,
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['username']),
                $dbConfig['password'] ? '-p' . escapeshellarg($dbConfig['password']) : '',
                escapeshellarg($dbConfig['database']),
                escapeshellarg($sqlFile->getPathname())
            );

            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                throw new \Exception('Error al ejecutar el comando de restauración SQL: ' . implode("\n", $output));
            }
            // --- Fin de la lógica ---

            Artisan::call('up');
            File::deleteDirectory($tempDir);
            
            return back()->with('success', 'Restauración completada exitosamente.');

        } catch (\Exception $e) {
            Artisan::call('up');
            if ($tempDir && File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            return back()->with('error', 'La restauración falló: ' . $e->getMessage());
        }
    }























}