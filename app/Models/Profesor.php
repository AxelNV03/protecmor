<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 1. IMPORTA EL TRAIT

/**
 * Class Profesor
 * 
 * @property int $id
 * @property int $user_id
 * @property string $especialidad
 * @property Carbon|null $fecha_ingreso
 * 
 * @property User $user
 * @property Collection|Clase[] $clases
 *
 * @package App\Models
 */
class Profesor extends Model
{
	use HasFactory; // <-- 2. USA EL TRAIT AQUÍ

	protected $table = 'profesores';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'fecha_ingreso' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'especialidad',
		'fecha_ingreso',
		'telefono_emergencia', // <-- AÑADIR ESTE
        'sexo', 
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function clases()
	{
		return $this->hasMany(Clase::class, 'profesor_id');
	}
}
