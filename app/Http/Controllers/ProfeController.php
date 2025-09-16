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


class ProfeController extends Controller
{
    public function index()
    {
        return view('profesores.dashboard');
    }



    public function data(): \Illuminate\Http\JsonResponse
    {
        $profesores = Profesor::with('user')->get(); 
        return response()->json($profesores);
    }



    public function store(SaveProfeRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            // Validar datos y crear usuario y profesor
            $validated = $request->validated();
            $profe = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'telefono'  => $validated['telefono'] ?? null,
                'estatus'   => 'activo',
            ]);
            $profe->assignRole('profesor');

            // Crear el registro en la tabla profesores
            $profe->profesor()->create([
                'especialidad'          => $validated['especialidad'],
                'telefono_emergencia'   => $validated['telefono_emergencia'] ?? null,
                'fecha_ingreso'         => $validated['fecha_ingreso'],
                'sexo'                  => $validated['sexo'],
            ]);
        });
        return redirect()->route('admin.index', ['tab' => 'profesores'])->with('success', 'Profesor creado correctamente');
    }




    public function update(SaveProfeRequest $request, Profesor $profesor): RedirectResponse
    {
       // 1. La validación ya ocurrió. Obtenemos solo los datos seguros.
       $validated = $request->validated();

       // 2. Actualizamos los datos del modelo User.
       $profesor->user->update([
           'name'       => $validated['name'],
           'email'      => $validated['email'],
           'telefono'   => $validated['telefono'] ?? null,
       ]);

       // 3. Si se proporcionó una nueva contraseña, la actualizamos.
       if (!empty($validated['password'])) {
           $profesor->user->password = Hash::make($validated['password']);
           $profesor->user->save();
       }

        // 4. Preparamos el array de datos solo para el Profesor
        $profesorData = [
            'especialidad'        => $validated['especialidad'],
            'sexo'                => $validated['sexo'],
            'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
        ];

        // 5. Condicionalmente, añadimos la fecha de ingreso al array
        if (!empty($validated['fecha_ingreso'])) {
            $profesorData['fecha_ingreso'] = $validated['fecha_ingreso'];
        }
        
        // 6. Hacemos UNA SOLA llamada a update() con el array que construimos
        $profesor->update($profesorData);

       return redirect()->route('admin.index', ['tab' => 'profesores'])->with('success', 'Profesor actualizado correctamente');
    }




    public function destroy(Profesor $profesor): RedirectResponse
    {
        // Opcional pero recomendado: guardar el usuario antes de borrar el profesor
        $user = $profesor->user;

        // Paso 1: Eliminar el registro 'hijo' (de la tabla 'profesores')
        $profesor->delete();

        // Paso 2: Eliminar el registro 'padre' (de la tabla 'users')
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.index', ['tab' => 'profesores'])->with('success', 'Profesor eliminado correctamente');
    }
}
