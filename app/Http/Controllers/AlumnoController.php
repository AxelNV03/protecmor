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


    public function data(): \Illuminate\Http\JsonResponse
    {
        $alumnos = Alumno::with('user')->get(); 
        return response()->json($alumnos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveAlumnoRequest $request): RedirectResponse
    {


        // Validar datos y crear usuario y alumno
        DB::transaction(function () use ($request) {
            // Validar datos y crear usuario y alumno
            $validated =  $request->validated();

            // Generar una contraseña segura
            $password = User::generatePassword();


            // Crear el usuario asociado al alumno
            $alumno = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($password),
                'telefono'  => $validated['telefono'] ?? null,
                'estatus'   => 'activo',
            ]);
            $alumno->assignRole('alumno');


            // Generar matrícula con los datos reales
            $matricula = Alumno::generarMatricula(
                $validated['name'],  // ← Pasar parámetros
                $validated['apeP'], 
                $validated['apeM']
            );

            // Crear el registro en la tabla alumnos
            $alumno->alumno()->create([
                'apeP'                => $validated['apeP'], 
                'apeM'                => $validated['apeM'],
                'direccion'           => $validated['direccion'] ?? null,
                'matricula'           => $matricula, // ← Asignar la matrícula generada
                'grupo_id'            => NULL, // Se asigna después
                'fecha_nacimiento'    => $validated['fecha_nacimiento'],
                'sexo'                => $validated['sexo'],
                'telefono_emergencia' => $validated['telefono_emergencia'] ?? null,
            ]);


            // Enviar email con las credenciales
            Mail::to($validated['email'])->send(new UserCredentialsMail(
                $validated['name'],
                $validated['email'],
                $password,
                'alumno'
            ));


        });
        // Redirigir con mensaje de éxito
        return redirect()->route('admin.index', ['tab' => 'alumnos'])->with('success', 'Alumno creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveAlumnoRequest $request, Alumno $alumno): RedirectResponse
    {
       // 1. La validación ya ocurrió. Obtenemos solo los datos seguros.
       $validated = $request->validated();

       // 2. Actualizamos los datos del modelo User.
       $alumno->user->update([
           'name'       => $validated['name'],
           'email'      => $validated['email'],
           'telefono'   => $validated['telefono'] ?? null,
           'estatus'    => $request->input('estatus'), // Asegurarse de que 'estatus' venga del formulario
       ]);

        if (!empty($validated['password'])) {
            $alumno->user->password = Hash::make($validated['password']);
            $alumno->save();
            
            // Enviar email con la nueva contraseña
            Mail::to($validated['email'])->send(new UserCredentialsMail(
                $validated['name'],
                $validated['email'],
                $validated['password'], // La contraseña en texto plano
                'alumno'
            ));
        }

        $alumnoData = [
            'apeP'                  => $validated['apeP'],  
            'apeM'                  => $validated['apeM'],
            'sexo'                  => $validated['sexo'],
            'direccion'             => $validated['direccion'] ?? null,
            'telefono_emergencia'   => $validated['telefono_emergencia'] ?? null,
        ];
        $alumno->update($alumnoData);
        return redirect()->route('admin.index', ['tab' => 'alumnos'])->with('success', 'Alumno actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno): RedirectResponse
    {
        // Eliminar el alumno y su usuario asociado
        $user = $alumno->user;

        // Usar transaction para asegurar integridad
        DB::transaction(function () use ($alumno, $user) {
            // Eliminar alumno y usuario
            $alumno->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.index', ['tab' => 'alumnos'])->with('success', 'Alumno eliminado correctamente');
    }
}
