<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Materiale
 * 
 * @property int $id
 * @property int $clase_id
 * @property string $titulo
 * @property string|null $archivo_url
 * @property string $tipo
 * @property Carbon $fecha_subida
 * 
 * @property Clase $clase
 *
 * @package App\Models
 */
class Materiale extends Model
{
	protected $table = 'materiales';
	public $timestamps = false;

	protected $casts = [
		'clase_id' => 'int',
		'fecha_subida' => 'datetime'
	];

	protected $fillable = [
		'clase_id',
		'titulo',
		'archivo_url',
		'tipo',
		'fecha_subida'
	];

	public function clase()
	{
		return $this->belongsTo(Clase::class);
	}
}
