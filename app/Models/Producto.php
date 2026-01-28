<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

     protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        // 'sucursal_id',
        'codigo',
        'nombre',
        'description',
        'imagen',
        'precio_compra',
        'precio_venta',
        'stock_minimo',
        'stock_maximo',
        'unidad_medida',
        'estado'
    ];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }
    // public function movimientosInventario()
    // {
    //     return $this->hasMany(MovimientoInventario::class, 'producto_id');
    // }
    //    public function detallesCompras()
    // {
    //     return $this->hasMany(DetallesCompra::class);
    // }

}
