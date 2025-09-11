<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\EventoRequest;

class EventoController extends Controller
{
    /**
     * Retorna todos los eventos en JSON (usado por fetch en la vista).
     */
    public function data()
    {
        return response()->json(Evento::all());
    }

    /**
     * Almacena un nuevo evento en la base de datos.
     */
    public function store(EventoRequest $request)
    {
        try {
            $evento = Evento::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Evento creado correctamente.',
                'evento'  => $evento
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear evento: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al crear el evento.'
            ], 500);
        }
    }

    /**
     * Actualiza un evento existente.
     */
    public function update(EventoRequest $request, Evento $evento)
    {
        try {
            $evento->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Evento actualizado correctamente.',
                'evento'  => $evento
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar evento: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar el evento.'
            ], 500);
        }
    }

    /**
     * Elimina un evento.
     */
    public function destroy(Evento $evento)
    {
        try {
            $evento->delete();

            return response()->json([
                'success' => true,
                'message' => 'Evento eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar evento: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el evento.'
            ], 500);
        }
    }
}