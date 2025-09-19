<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Http\Requests\EventoRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class EventoController extends Controller
{
    /**
     * Retorna todos los eventos en JSON para FullCalendar.
     */
    public function data(): JsonResponse
    {
        $eventos = Evento::all()->map(function ($evento) {
            return [
                'id' => $evento->id,
                'title' => $evento->nombre,
                'start' => $evento->fecha->toDateString(),
                'end' => $evento->fecha->toDateString(),
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

    /**
     * Página de eventos para la vista pública o admin.
     */
    public function indexPublic()
    {
        return view('eventosPublic', [  // nombre del archivo de la vista sin .blade.php
            'isAdmin' => false, 
            'eventsDataUrl' => route('eventos.public.data') // nombre de ruta definido
        ]);
    }

    // Métodos CRUD para admin
    public function store(EventoRequest $request): JsonResponse
    {
        try {
            $evento = Evento::create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Evento guardado con éxito.',
                'evento' => [
                    'id' => $evento->id,
                    'title' => $evento->nombre,
                    'start' => $evento->fecha->toDateString(),
                    'end' => $evento->fecha->toDateString(),
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
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear evento: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al guardar el evento.'], 500);
        }
    }

    public function update(EventoRequest $request, Evento $evento): JsonResponse
    {
        try {
            $evento->update($request->validated());
            return response()->json(['success' => true, 'message' => 'Evento actualizado con éxito.', 'evento' => $evento]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar evento: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al actualizar el evento.'], 500);
        }
    }

    public function destroy(Evento $evento): JsonResponse
    {
        try {
            $evento->delete();
            return response()->json(['success' => true, 'message' => 'Evento eliminado con éxito.']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar evento: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al eliminar el evento.'], 500);
        }
    }
}