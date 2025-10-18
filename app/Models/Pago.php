<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pago
 * 
 * @property int $id
 * @property int $alumno_id
 * @property string $tipo
 * @property float $monto
 * @property Carbon $fecha_pago
 * @property string $estado
 * 
 * @property Alumno $alumno
 *
 * @package App\Models
 */
class Pago extends Model
{
	protected $table = 'pagos';
	public $timestamps = false;

	protected $casts = [
		'alumno_id' => 'int',
		'monto' => 'float',
		'fecha_pago' => 'date'
	];

	protected $fillable = [
		'alumno_id',
		'tipo',
		'monto',
		'fecha_pago',
		'estado'
	];

	public function alumno()
	{
		return $this->belongsTo(Alumno::class);
	}
}
