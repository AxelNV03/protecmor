<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
	protected $table = 'campos_formativos';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'tipo'
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
