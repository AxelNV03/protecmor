<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Respaldo
 * 
 * @property int $id
 * @property string $nombre_archivo
 * @property int $usuario_id
 * @property Carbon $fecha_creacion
 * @property string $ruta
 * @property int|null $tamano_bytes
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Respaldo extends Model
{
	protected $table = 'respaldos';
	public $timestamps = false;

	protected $casts = [
		'usuario_id' => 'int',
		'fecha_creacion' => 'datetime',
		'tamano_bytes' => 'int'
	];

	protected $fillable = [
		'nombre_archivo',
		'usuario_id',
		'fecha_creacion',
		'ruta',
		'tamano_bytes'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'usuario_id');
	}
}
