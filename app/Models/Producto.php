<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 * * @property int $id
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
    // Uso de HasFactory es una convención moderna de Laravel
    use HasFactory;
    
    // No especificamos $table si es 'productos', pero se mantiene si es un estándar de Reliese
    protected $table = 'productos';
    
    // Su proyecto no usa timestamps.
    public $timestamps = false;

    protected $casts = [
        'precio' => 'float',
        'costo' => 'float',
        'disponible' => 'boolean', // Usar 'boolean' en lugar de 'bool' es más robusto
        'visible' => 'boolean'
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
    
    /**
     * Define las categorías posibles para el producto (centralizado).
     * @return array
     */
    public static function getCategorias()
    {
        return [
            'Uniformes',
            'Equipamiento',
            'Kits',
            'Material Didáctico',
            'Accesorios',
            'Merchandising',
        ];
    }
}