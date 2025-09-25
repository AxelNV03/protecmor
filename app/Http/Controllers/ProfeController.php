<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;        // <--- Agregar esto
use App\Models\Profesor;   // <--- Si no lo agregaste todavía
use App\Http\Requests\SaveProfeRequest; // <-- CAMBIO CLAVE: Usar el request correcto
use Illuminate\Support\Facades\Hash; // <--- Para Hash::make
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View; // <-- Importar View
use Illuminate\Support\Facades\DB; // <-- ¡IMPORTANTE!
use Illuminate\Support\Facades\Mail; // ← Agregar esta línea
use App\Mail\UserCredentialsMail;

class ProfeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('profesores.dashboard');
    }

    /**
     * Return JSON data for DataTables.
     */
    public function data(): \Illuminate\Http\JsonResponse
    {
        $profesores = Profesor::with('user')->get(); 
        return response()->json($profesores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveProfeRequest $request): RedirectResponse
    {
        $validated =  $request->validated();    // Validar datos
        $password = User::generatePassword();   // Generar una contraseña segura
        $profe = null;                          // Declarar la variable antes de la transacción

        // Usar una transacción para asegurar la integridad de los datos
        DB::transaction(function () use ($validated, $password, &$profe) { // Pasar por referencia
            // Crear el usuario asociado al profesor
            $profe = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($password),
                'telefono'  => $validated['telefono'] ?? null,
                'estatus'   => 'activo',
            ]);
            $profe->assignRole('profesor');
            
            // Crear el registro en la tabla profesores
            $profe->profesor()->create([
                'especialidad'          => $validated['especialidad'],
                'telefono_emergencia'   => $validated['telefono_emergencia'] ?? null,
                'sexo'                  => $validated['sexo'],
            ]);
        });

        // Enviar email con las credenciales
        if($profe) {
            Mail::to($profe->email)->send(new UserCredentialsMail(
                $profe->name,
                $profe->email,
                $password,
                'profesor'  // ← Tipo de usuario
            ));
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.index', ['tab' => 'profesores'])->with('success', 'Profesor creado correctamente');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveProfeRequest $request, Profesor $profesor): RedirectResponse
    {
        // 1. La validación ya ocurrió. Obtenemos solo los datos seguros.
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $profesor) {
           // 2. Actualizamos los datos del modelo User.
            $profesor->user->update([
                'name'       => $validated['name'],
                'email'      => $validated['email'],
                'telefono'   => $validated['telefono'] ?? null,
                'estatus'    => $validated['estatus'], // Asegurarse de que 'estatus' venga del formulario
            ]);
       
            // 3. Preparamos el array de datos solo para el Profesor
            $profesorData = [
                'especialidad'        => $validated['especialidad'],
                'sexo'                => $validated['sexo'],
                'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
            ];
            
            // 6. Hacemos UNA SOLA llamada a update() con el array que construimos
            $profesor->update($profesorData);

            if (!empty($validated['password'])) {
                $profesor->user->password = Hash::make($validated['password']);
                $profesor->user->save();
            }
        });


        if (!empty($validated['password'])) {
            // Enviar email con la nueva contraseña
            Mail::to($validated['email'])->send(new UserCredentialsMail(
                $validated['name'],
                $validated['email'],
                $validated['password'], // La contraseña en texto plano
                'profesor'  // ← Tipo de usuario
            ));
        }



       return redirect()->route('admin.index', ['tab' => 'profesores'])->with('success', 'Profesor actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profesor $profesor): RedirectResponse
    {
        // Verificar que sea super admin o admin antes de eliminar
        if (!auth()->user()->hasAnyRole(['super admin', 'admin'])) {
            return redirect()->route('admin.index', ['tab' => 'profesores'])
                ->with('error', 'No tienes permiso para eliminar profesores.');
        }

        DB::transaction(function () use ($profesor) {
            // Opcional pero recomendado: guardar el usuario antes de borrar el profesor
            $user = $profesor->user;

            // Paso 1: Eliminar el registro 'hijo' (de la tabla 'profesores')
            $profesor->delete();

            // Paso 2: Eliminar el registro 'padre' (de la tabla 'users')
            if ($user) {
                $user->roles()->detach(); // Primero, eliminamos los roles asociados
                $user->delete();
            }
        });

        return redirect()->route('admin.index', ['tab' => 'profesores'])->with('success', 'Profesor eliminado correctamente');
    }
}
