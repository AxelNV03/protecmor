<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class CamposFormativo
 * 
 * @property int $id
 * @property string $nombre
 * @property string $tipo
 * 
 * @property Collection|Clase[] $clases
 * @property Collection|Constancia[] $constancias
 *
 * @package App\Models
 */
class CamposFormativo extends Model
{
	use HasFactory;

	protected $table = 'campos_formativos';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'tipo',
		'descripcion'
	];

	public function clases()
	{
		return $this->hasMany(Clase::class, 'campo_formativo_id');
	}

	public function constancias()
	{
		return $this->hasMany(Constancia::class, 'taller_id');
	}
}
