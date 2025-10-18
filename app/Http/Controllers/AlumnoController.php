<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;        // <--- Agregar esto
use App\Models\Alumno;   // <--- Si no lo agregaste todavía
use App\Http\Requests\SaveAlumnoRequest; // <-- CAMBIO CLAVE: Usar el request correcto
use Illuminate\Support\Facades\Hash; // <--- Para Hash::make
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\DB; // <-- ¡IMPORTANTE!
use Illuminate\Support\Facades\Mail; // ← Agregar esta línea
use App\Mail\UserCredentialsMail;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retornar la vista principal de alumnos
        return view('alumnos.dashboard');
    }

    /**
     * Return JSON data for DataTables.
     */
    public function data(): \Illuminate\Http\JsonResponse
    {
        $alumnos = Alumno::with(['user', 'grupo'])->get(); 
        return response()->json($alumnos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveAlumnoRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $password  = User::generatePassword();
        
        $user = DB::transaction(function () use ($validated, $password) {
            // 1) Crear usuario
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($password),
                'telefono' => $validated['telefono'] ?? null,
                'estatus'  => 'activo',
            ]);
            $user->assignRole('alumno');

            // 2) Generar matrícula real
            $matricula = Alumno::generarMatricula(
                $validated['name'],
                $validated['apeP'],
                $validated['apeM']
            );

            // 3) Crear Alumno relacionado (esto disparará el evento created del modelo)
            $user->alumno()->create([
                'apeP'                => $validated['apeP'],
                'apeM'                => $validated['apeM'],
                'direccion'           => $validated['direccion'] ?? null,
                'matricula'           => $matricula,
                'grupo_id'            => null,
                'fecha_nacimiento'    => $validated['fecha_nacimiento'],
                'sexo'                => $validated['sexo'],
                'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
            ]);

            return $user; 
        });

        // 4) Enviar credenciales SOLO tras commit exitoso
        DB::afterCommit(function () use ($validated, $password) {
            Mail::to($validated['email'])->send(new UserCredentialsMail(
                $validated['name'],
                $validated['email'],
                $password,
                'alumno'
            ));
        });

        // Redirigir con mensaje de éxito
        return redirect()
            ->route('admin.index', ['tab' => 'alumnos'])
            ->with('success', 'Alumno creado correctamente (pago de inscripción generado automáticamente).');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveAlumnoRequest $request, Alumno $alumno): RedirectResponse
    {
       $validated = $request->validated();  // Validar datos
       
        DB::transaction(function () use ($validated, $alumno) {
       
            // Actualizamos los datos del modelo User.
            $alumno->user->update([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'telefono'   => $validated['telefono'] ?? null,
                'estatus'    => $validated['estatus'], // Asegurarse de que 'estatus' venga del formulario
            ]);

            // Preparamos el array de datos solo para el Alumno
            $alumnoData = [
                'apeP'                  => $validated['apeP'],  
                'apeM'                  => $validated['apeM'],
                'sexo'                  => $validated['sexo'],
                'direccion'             => $validated['direccion'] ?? null,
                'telefono_emergencia'   => $validated['telefono_emergencia'] ?? null,
            ];
            $alumno->update($alumnoData);

            // Si se proporcionó una nueva contraseña, actualizarla y enviar email
            if (!empty($validated['password'])) {
                $alumno->user->password = Hash::make($validated['password']);
                $alumno->user->save();
                
            }
        });

        if (!empty($validated['password'])) {
            // Enviar email con la nueva contraseña
            Mail::to($validated['email'])->send(new UserCredentialsMail(
                $validated['name'],
                $validated['email'],
                $validated['password'], // La contraseña en texto plano
                'alumno'
            ));
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.index', ['tab' => 'alumnos'])->with('success', 'Alumno actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno): RedirectResponse
    {
        // 1. Autorización (se mantiene igual)
        if (!auth()->user()->hasAnyRole(['super admin', 'admin'])) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. Usar una transacción para asegurar que ambas operaciones ocurran juntas
        DB::transaction(function () use ($alumno) {
            $user = $alumno->user;

            // Paso A: Borrado lógico del perfil de alumno
            $alumno->delete();

            // Paso B: Borrado lógico del usuario asociado
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.index', ['tab' => 'alumnos'])
            ->with('success', 'El alumno y su cuenta de usuario han sido archivados.');
    }
}
