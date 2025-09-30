<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 1. Importar
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- 1. Importa el trait


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
	use HasFactory, SoftDeletes; // <-- 2. Añadir esta línea


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


    /**
     * Generates a unique key for the group.
	 */
	public static function generarClave(string $nombre): string
	{
		do {
			// --- 1. Obtenemos todas las partes ---
			$anio = date('y'); // Año en 2 dígitos

			// Iniciales (3 letras)
			$slug = Str::slug($nombre, '');
			$iniciales = Str::upper(substr($slug, 0, 3));
			$iniciales = str_pad($iniciales, 3, 'X');

			// Tres caracteres aleatorios
			$random = Str::upper(Str::random(3));

			// Próxima posición en la tabla (ej. 0001)
			$proximoId = (self::max('id') ?? 0) + 1;
			$numeroPosicion = str_pad($proximoId, 4, '0', STR_PAD_LEFT);

			// --- 2. Unimos todo en el formato solicitado ---
			$clave = "G{$anio}-{$iniciales}-{$random}-{$numeroPosicion}";
			
		// El bucle asegura que la clave sea 100% única en caso de colisión
		} while (self::where('clave', $clave)->exists());
		
		return $clave;
	}
}
