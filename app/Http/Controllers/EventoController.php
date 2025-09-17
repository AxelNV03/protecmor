<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Http\Requests\EventoRequest;
use Illuminate\Support\Facades\Log;

class EventoController extends Controller
{
    public function data()
    {
        $eventos = Evento::all()->map(function ($evento) {
            return [
                'id' => $evento->id,
                'title' => $evento->nombre,
                'start' => $evento->fecha->toDateString(),
                'extendedProps' => [
                    'tipo' => $evento->tipo,
                    'hora' => $evento->hora ? $evento->hora->format('H:i') : null,
                    'duracion' => $evento->duracion,
                    'costo' => $evento->costo,
                    'lugar' => $evento->lugar,
                    'descripcion' => $evento->descripcion,
                    'publico' => $evento->publico,
                    'incluido_mensualidad' => $evento->incluido_mensualidad,
                ]
            ];
        });

        return response()->json($eventos);
    }

    public function store(EventoRequest $request)
    {
        try {
            $evento = Evento::create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Evento creado con éxito.',
                'evento' => $evento
            ], 201); // 201 es el código de éxito para "Created"
        } catch (\Exception $e) {
            Log::error('Error al crear evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el evento.'
            ], 500);
        }
    }

    public function update(EventoRequest $request, Evento $evento)
    {
        try {
            $evento->update($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Evento actualizado con éxito.',
                'evento' => $evento
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar evento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar el evento.'
            ], 500);
        }
    }

    public function destroy(Evento $evento)
    {
        try {
            $evento->delete();
            return response()->json([
                'success' => true,
                'message' => 'Evento eliminado con éxito.'
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