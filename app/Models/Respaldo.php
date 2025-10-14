<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- 1. Importación correcta
use App\Models\User; // <-- 2. Importación correcta

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

	protected $appends = ['tamano_formateado'];
	protected $casts = [
		'usuario_id' => 'int',
		'fecha_creacion' => 'datetime',
		'tamano_bytes' => 'int'
	];

	protected $fillable = [
		'nombre_archivo',
		'usuario_id',
		'ruta',
		'tamano_bytes'
	];

	public function usuario(): BelongsTo
	{
		return $this->belongsTo(User::class, 'usuario_id');
	}

	public function getTamanoFormateadoAttribute(): string
    {
        $kb = $this->tamano_bytes / 1024;
        
        if ($kb < 1024) {
            return round($kb, 2) . ' KB';
        }
        
        $mb = $kb / 1024;
        return round($mb, 2) . ' MB';
    }
}
