<?php

namespace App\Observers;

use App\Models\Alumno;
use App\Models\Pago;
use Illuminate\Support\Carbon;

class AlumnoObserver
{
     /** Hace que los eventos se ejecuten tras el commit */
    public bool $afterCommit = true;

    /**
     * Handle the Alumno "created" event.
     */
    public function created(Alumno $alumno): void
    {
        $fechaBase = optional($alumno->user)->created_at ?? now();
        $fechaVenc = Carbon::parse($fechaBase)->addDays(30)->toDateString();

        Pago::create([
            'alumno_id'  => $alumno->id,
            'tipo'       => 'inscripcion',
            'monto'      => config('pagos.inscripcion_monto', 0.00),
            'fecha_pago' => $fechaVenc,
            'estado'     => 'pendiente',
        ]);
    }

    /**
     * Handle the Alumno "updated" event.
     */
    public function updated(Alumno $alumno): void
    {
        //
    }

    /**
     * Handle the Alumno "deleted" event.
     */
    public function deleted(Alumno $alumno): void
    {
        //
    }

    /**
     * Handle the Alumno "restored" event.
     */
    public function restored(Alumno $alumno): void
    {
        //
    }

    /**
     * Handle the Alumno "force deleted" event.
     */
    public function forceDeleted(Alumno $alumno): void
    {
        //
    }
}
