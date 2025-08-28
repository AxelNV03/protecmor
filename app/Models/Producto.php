<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 * 
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property float $precio
 * @property float|null $costo
 * @property string|null $proveedor
 * @property string $categoria
 * @property bool $disponible
 * @property string|null $imagen
 * @property bool $visible
 *
 * @package App\Models
 */
class Producto extends Model
{
	protected $table = 'productos';
	public $timestamps = false;

	protected $casts = [
		'precio' => 'float',
		'costo' => 'float',
		'disponible' => 'bool',
		'visible' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'precio',
		'costo',
		'proveedor',
		'categoria',
		'disponible',
		'imagen',
		'visible'
	];
}
