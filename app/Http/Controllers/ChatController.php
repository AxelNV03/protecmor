<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Mensaje;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{
    private function canParticipate($user, Clase $clase): bool
    {
        if ($user->hasRole('profesor')) {
            return optional($user->profesor)->id === $clase->profesor_id;
        }
        if ($user->hasRole('alumno')) {
            return optional($user->alumno)->grupo_id === $clase->grupo_id;
        }
        return false; // admin no participa
    }

    public function index(Request $request, Clase $clase)
    {
        abort_unless($this->canParticipate($request->user(), $clase), 403);

        $msgs = Mensaje::with(['user:id,name'])
            ->where('clase_id', $clase->id)
            ->orderBy('fecha_envio', 'asc')
            ->take(200)
            ->get()
            ->map(fn($m) => [
                'id'        => $m->id,
                'user_id'   => $m->usuario_id,
                'user_name' => $m->user?->name ?? 'Usuario',
                'contenido' => $m->contenido,
                'fecha'     => optional($m->fecha_envio)->format('Y-m-d H:i:s'),
            ]);

        return response()->json($msgs);
    }

    public function store(Request $request, Clase $clase)
    {
        abort_unless($this->canParticipate($request->user(), $clase), 403);

        $data = $request->validate([
            'contenido' => ['required','string','max:2000'],
        ]);

        $msg = Mensaje::create([
            'clase_id'   => $clase->id,
            'usuario_id' => $request->user()->id,
            'contenido'  => $data['contenido'],
            'fecha_envio'=> now(),
        ]);

        $msg->load('user:id,name');
        event(new \App\Events\MessageSent($msg));

        return response()->json([
            'id'        => $msg->id,
            'user_id'   => $msg->usuario_id,
            'user_name' => $msg->user?->name ?? 'Usuario',
            'contenido' => $msg->contenido,
            'fecha'     => $msg->fecha_envio->format('Y-m-d H:i:s'),
        ], 201);
    }
}
