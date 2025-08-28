<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Constancia
 * 
 * @property int $id
 * @property int $alumno_id
 * @property int $taller_id
 * @property string $archivo_url
 * @property Carbon $fecha_emision
 * 
 * @property Alumno $alumno
 * @property CamposFormativo $campos_formativo
 *
 * @package App\Models
 */
class Constancia extends Model
{
	protected $table = 'constancias';
	public $timestamps = false;

	protected $casts = [
		'alumno_id' => 'int',
		'taller_id' => 'int',
		'fecha_emision' => 'datetime'
	];

	protected $fillable = [
		'alumno_id',
		'taller_id',
		'archivo_url',
		'fecha_emision'
	];

	public function alumno()
	{
		return $this->belongsTo(Alumno::class);
	}

	public function campos_formativo()
	{
		return $this->belongsTo(CamposFormativo::class, 'taller_id');
	}
}
