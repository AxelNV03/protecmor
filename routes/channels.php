<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Clase;

/*
|--------------------------------------------------------------------------
| Canales de Broadcast
|--------------------------------------------------------------------------
|
| Aquí se pueden registrar los canales de broadcast que la aplicación soporta.
| El canal por defecto "App.Models.User.{id}" se usa para notificaciones
| personales (por usuario). Debajo se agrego el canal del chat por clase.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/*
|--------------------------------------------------------------------------
| Canal del Chat de Clase
|--------------------------------------------------------------------------
|
| Permite que solo los profesores encargados de la clase y los alumnos
| que pertenecen al grupo de esa clase puedan conectarse al chat.
|
*/

Broadcast::channel('clase.{claseId}', function ($user, $claseId) {
    $clase = Clase::find($claseId);
    if (! $clase) {
        return false;
    }

    // Profesor dueño de la clase
    if ($user->hasRole('profesor')) {
        return optional($user->profesor)->id === $clase->profesor_id;
    }

    // Alumno del grupo de la clase
    if ($user->hasRole('alumno')) {
        return optional($user->alumno)->grupo_id === $clase->grupo_id;
    }

    // Otros roles (admin/super admin) no participan en el chat
    return false;
});
