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
		'fecha_nacimiento' => 'date',
	];

	protected $appends = ['edad'];
	protected $fillable = [
		'user_id',
		'apeP',
		'apeM',
		'matricula',
		'grupo_id',
		'fecha_nacimiento',
		'sexo',
		'telefono_emergencia',
		'estatus',
		'direccion',
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
	public static function generarMatricula($nombre, $apeP, $apeM): string
	{
		// Obtener las iniciales
		$inicialApeP = $apeP ? strtoupper(substr(trim($apeP), 0, 1)) : 'X';
		$inicialApeM = $apeM ? strtoupper(substr(trim($apeM), 0, 1)) : 'X';
		$inicialNombre = $nombre ? strtoupper(substr(trim($nombre), 0, 1)) : 'X';
		
		// Obtener los últimos 2 dígitos del año actual
		$anio = date('y'); // Ejemplo: 24 para 2024
		
		// Obtener el próximo ID (posición en la tabla)
		$proximoId = self::max('id') + 1; // Último ID + 1
		$numeroPosicion = str_pad($proximoId, 4, '0', STR_PAD_LEFT); // Formato 001, 002, etc.
		
		// Crear la matrícula
		$matricula = "PTCMR{$anio}{$inicialApeP}{$inicialApeM}{$inicialNombre}{$numeroPosicion}";
		
		return $matricula;
	}

	// 👇 4. Define el Accessor para 'edad'
    public function getEdadAttribute(): int
    {
        // Carbon calcula la edad automáticamente
        return $this->fecha_nacimiento->age;
	}
}
