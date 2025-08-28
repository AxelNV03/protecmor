<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Mensaje
 * 
 * @property int $id
 * @property int $clase_id
 * @property int $usuario_id
 * @property string $contenido
 * @property Carbon $fecha_envio
 * 
 * @property Clase $clase
 * @property User $user
 *
 * @package App\Models
 */
class Mensaje extends Model
{
	protected $table = 'mensajes';
	public $timestamps = false;

	protected $casts = [
		'clase_id' => 'int',
		'usuario_id' => 'int',
		'fecha_envio' => 'datetime'
	];

	protected $fillable = [
		'clase_id',
		'usuario_id',
		'contenido',
		'fecha_envio'
	];

	public function clase()
	{
		return $this->belongsTo(Clase::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'usuario_id');
	}
}
