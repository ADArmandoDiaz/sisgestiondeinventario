<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Lote extends Model
{
    protected $table = 'lotes';

    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'codigo_lote',       // opcional
        'cantidad_actual',   // cantidad disponible en ese lote
        'fecha_ingreso',
        'fecha_vencimiento',
        'estado'             // activo/inactivo

    ];
     protected $casts = [
        // La columna fecha_ingreso DEBE ser un objeto DateTime
        'fecha_ingreso'     => 'datetime', 
        // La columna fecha_vencimiento DEBE ser un objeto DateTime (es nullable)
        'fecha_vencimiento' => 'datetime', 
        'estado'            => 'boolean',
    ];
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    // public function inventarioSucursalLotes()
    // {
    //     return $this->hasMany(InventarioSucursalLote::class, 'lote_id');
    // }
    // public function movimientosInventario()
    // {
    //     return $this->hasMany(MovimientoInventario::class, 'lote_id');
    // }
    // public function detallesCompras()
    // {
    //     return $this->hasMany(DetallesCompra::class, 'lote_id');
    // }
}
