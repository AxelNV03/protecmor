<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Calificacione
 * 
 * @property int $id
 * @property int $alumno_id
 * @property int $clase_id
 * @property float|null $calificacion
 * @property string|null $nivel_desempeno
 * 
 * @property Alumno $alumno
 * @property Clase $clase
 *
 * @package App\Models
 */
class Calificacione extends Model
{
	protected $table = 'calificaciones';
	public $timestamps = false;

	protected $casts = [
		'alumno_id' => 'int',
		'clase_id' => 'int',
		'calificacion' => 'float'
	];

	protected $fillable = [
		'alumno_id',
		'clase_id',
		'calificacion',
		'nivel_desempeno'
	];

	public function alumno()
	{
		return $this->belongsTo(Alumno::class);
	}

	public function clase()
	{
		return $this->belongsTo(Clase::class);
	}
}
