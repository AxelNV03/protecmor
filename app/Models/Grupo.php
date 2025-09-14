<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 1. Importar


/**
 * Class Grupo
 * 
 * @property int $id
 * @property string $clave
 * @property string $nombre
 * @property string|null $generacion
 * @property string|null $observaciones
 * 
 * @property Collection|Alumno[] $alumnos
 * @property Collection|Clase[] $clases
 *
 * @package App\Models
 */
class Grupo extends Model
{
	use HasFactory; // <-- 2. Añadir esta línea


	protected $table = 'grupos';
	public $timestamps = false;

	protected $fillable = [
		'clave',
		'nombre',
		'generacion',
		'observaciones'
	];

	public function alumnos()
	{
		return $this->hasMany(Alumno::class);
	}

	public function clases()
	{
		return $this->hasMany(Clase::class);
	}

	// Un grupo tiene muchos profesores A TRAVÉS de las clases
	public function profesores() {
		return $this->belongsToMany(Profesor::class, 'clases');
	}

}
