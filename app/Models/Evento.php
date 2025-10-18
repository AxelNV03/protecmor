<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Evento
 * 
 * @property int $id
 * @property string $nombre
 * @property string $tipo
 * @property Carbon $fecha
 * @property Carbon|null $hora
 * @property string|null $duracion
 * @property float|null $costo
 * @property string|null $lugar
 * @property string|null $descripcion
 * @property string $publico
 * @property bool $incluido_mensualidad
 *
 * @package App\Models
 */
class Evento extends Model
{
	protected $table = 'eventos';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'hora' => 'datetime',
		'costo' => 'float',
		'incluido_mensualidad' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'tipo',
		'fecha',
		'hora',
		'duracion',
		'costo',
		'lugar',
		'descripcion',
		'publico', //general o alumnos
		'incluido_mensualidad'
	];
}
