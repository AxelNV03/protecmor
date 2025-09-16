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

	// Generar una matrícula única para el alumno
	public static function generarMatricula(): string
    {
        // $letras = chr(rand(65, 90)) . chr(rand(65, 90)); // Genera 2 letras mayúsculas
        // $anio = date('Y');
        // $numeros = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 4 números con ceros a la izquierda

        // $matricula = $letras . $anio . $numeros;

        // // Opcional: Verifica si la matrícula ya existe y genera una nueva si es necesario
        // while (self::where('matricula', $matricula)->exists()) {
        //     $numeros = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        //     $matricula = $letras . $anio . $numeros;
        // }

        // return $matricula;
    }
}
