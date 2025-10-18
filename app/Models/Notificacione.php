<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notificacione
 * 
 * @property int $id
 * @property int $usuario_id
 * @property string $tipo
 * @property string $titulo
 * @property string $mensaje
 * @property Carbon $fecha_creacion
 * @property bool $leida
 * @property Carbon|null $fecha_leida
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Notificacione extends Model
{
	protected $table = 'notificaciones';
	public $timestamps = false;

	protected $casts = [
		'usuario_id' => 'int',
		'fecha_creacion' => 'datetime',
		'leida' => 'bool',
		'fecha_leida' => 'datetime'
	];

	protected $fillable = [
		'usuario_id',
		'tipo',
		'titulo',
		'mensaje',
		'fecha_creacion',
		'leida',
		'fecha_leida'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'usuario_id');
	}
}
