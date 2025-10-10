<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str; // <-- AÑADE ESTA LÍNEA


/**
 * Class Clase
 * 
 * @property int $id
 * @property int $grupo_id
 * @property int $profesor_id
 * @property int $campo_formativo_id
 * @property Carbon|null $fecha_inicio
 * @property Carbon|null $fecha_fin
 * 
 * @property Grupo $grupo
 * @property Profesore $profesore
 * @property CamposFormativo $campos_formativo
 * @property Collection|Calificacione[] $calificaciones
 * @property Collection|Materiale[] $materiales
 * @property Collection|Mensaje[] $mensajes
 *
 * @package App\Models
 */
class Clase extends Model
{
	use HasFactory;

	protected $table = 'clases';
	public $timestamps = false;

    protected $casts = [
        'grupo_id'           => 'int',
        'profesor_id'        => 'int',
        'campo_formativo_id' => 'int',
        'fecha_inicio'       => 'date', // 'date' es más preciso que 'datetime'
        'fecha_fin'          => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'grupo_id',
        'profesor_id',
        'campo_formativo_id',
        'fecha_inicio',
        'fecha_fin',
        'clave',         // 👈 Añadido
        'nombre',        // 👈 Añadido
        'descripcion',   // 👈 Añadido
        'estado',        // 👈 Añadido
    ];

	public function grupo()
	{
		return $this->belongsTo(Grupo::class);
	}

	public function profesor()
	{
		return $this->belongsTo(Profesor::class, 'profesor_id');
	}

	public function campoFormativo()
	{
		return $this->belongsTo(CamposFormativo::class, 'campo_formativo_id');
	}

	public function calificaciones()
	{
		return $this->hasMany(Calificacione::class);
	}

	public function materiales()
	{
		return $this->hasMany(Materiale::class);
	}

	public function mensajes()
	{
		return $this->hasMany(Mensaje::class);
	}

    public static function generarClave(string $nombre): string
    {
        do {
            $anio = date('y'); // Año en 2 dígitos

            // Genera 3 iniciales a partir del nombre
            $slug = Str::slug($nombre, '');
            $iniciales = Str::upper(substr($slug, 0, 3));
            $iniciales = str_pad($iniciales, 3, 'X'); // Rellena si el nombre es corto

            // Genera 3 caracteres aleatorios para asegurar unicidad
            $random = Str::upper(Str::random(3));
            
            $clave = "CL{$anio}-{$iniciales}-{$random}";

        // Vuelve a generar si la clave ya existe
        } while (self::where('clave', $clave)->exists());
        
        return $clave;
    }

}
