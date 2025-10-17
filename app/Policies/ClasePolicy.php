<?php

namespace App\Policies;

use App\Models\Clase;
use App\Models\User;

class ClasePolicy
{
    /**
     * Puede ver la clase
     */
    public function view(User $user, Clase $clase): bool
    {
        if ($user->hasAnyRole(['super admin', 'admin'])) {
            return true;
        }

        if ($user->hasRole('profesor')) {
            return optional($user->profesor)->id === $clase->profesor_id;
        }

        if ($user->hasRole('alumno')) {
            return $clase->alumnos()->where('alumno_id', optional($user->alumno)->id)->exists();
        }

        return false;
    }

    /**
     * Puede gestionar contenido (subir materiales, asistencias, etc.)
     */
    public function manage(User $user, Clase $clase): bool
    {
        return $user->hasRole('profesor')
            && optional($user->profesor)->id === $clase->profesor_id
            && $clase->estado === 'en curso';
    }

    /**
     * Puede finalizar la clase (solo profesor dueño y en curso)
     */
    public function finalize(User $user, Clase $clase): bool
    {
        return $this->manage($user, $clase);
    }
}
