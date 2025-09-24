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
    public function update(Request $request, string $id)
    {
        //
        return redirect()->route('admin.index', ['tab' => 'alumnos'])->with('success', 'Alumno creado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
