<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Añadido para usar factories


/**
 * Class Alumno
 * 
 * @property int $id
 * @property int $user_id
 * @property string|null $matricula
 * @property int $grupo_id
 * @property Carbon $fecha_nacimiento
 * @property string|null $telefono
 * @property string|null $sexo
 * 
 * @property User $user
 * @property Grupo $grupo
 * @property Collection|Calificacione[] $calificaciones
 * @property Collection|Constancia[] $constancias
 * @property Collection|Pago[] $pagos
 *
 * @package App\Models
 */
class Alumno extends Model
{
	use HasFactory; // <-- AÑADE ESTA LÍNE

	protected $table = 'alumnos';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'grupo_id' => 'int',
		'fecha_nacimiento' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'matricula',
		'grupo_id',
		'fecha_nacimiento',
		'telefono_emergencia',
		'sexo',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function grupo()
	{
		return $this->belongsTo(Grupo::class);
	}

	public function calificaciones()
	{
		return $this->hasMany(Calificacione::class);
	}

	public function constancias()
	{
		return $this->hasMany(Constancia::class);
	}

	public function pagos()
	{
		return $this->hasMany(Pago::class);
	}
}
