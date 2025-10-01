<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 1. IMPORTA EL TRAIT
use Illuminate\Database\Eloquent\SoftDeletes; // <-- 1. Importa el trait
use Illuminate\Database\Eloquent\Relations\BelongsTo;



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
	use HasFactory, SoftDeletes; // <-- 2. USA EL TRAIT AQUÍ

	protected $table = 'profesores';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'fecha_nacimiento' => 'date', // 👈 Añade esto
	];

	protected $appends = ['edad'];
	protected $fillable = [
		'user_id',
		'apeP',
		'apeM',
		'matricula',
		'fecha_nacimiento',
		'especialidad',
		'telefono_emergencia', // <-- AÑADIR ESTE
        'sexo', 
		'estatus' // <-- AÑADIR ESTE
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function clases()
	{
		return $this->hasMany(Clase::class, 'profesor_id');
	}

	// Un profesor puede enseñar en muchos grupos A TRAVÉS de las clases
	public function grupos() {
	    return $this->belongsToMany(Grupo::class, 'clases');
	}

		// Generar una matrícula única para el profe
	public static function generarMatricula($nombre, $apeP, $apeM): string
	{
		// Obtener las iniciales
		$inicialApeP = $apeP ? strtoupper(substr(trim($apeP), 0, 1)) : 'X';
		$inicialApeM = $apeM ? strtoupper(substr(trim($apeM), 0, 1)) : 'X';
		$inicialNombre = $nombre ? strtoupper(substr(trim($nombre), 0, 1)) : 'X';
		$anio = date('y'); // Ejemplo: 24 para 2024
		
		do{
			// Obtener el próximo ID (posición en la tabla)
			$proximoId = self::max('id') + 1; // Último ID + 1
			$numeroPosicion = str_pad($proximoId, 4, '0', STR_PAD_LEFT); // Formato 001, 002, etc.
			
			// Crear la matrícula
			$matricula = "PTCMR{$anio}PRO{$inicialApeP}{$inicialApeM}{$inicialNombre}{$numeroPosicion}";
		} while (self::where('matricula', $matricula)->exists());
		
		return $matricula;
	}

    public function getEdadAttribute(): ?int // 👈 Es bueno usar ?int por si la fecha es nula
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }
        return Carbon::parse($this->fecha_nacimiento)->age;
    }
}
