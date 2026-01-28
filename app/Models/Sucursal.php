<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Sucursal extends Model
// {
//     /** @use HasFactory<\Database\Factories\SucursalFactory> */
//     use HasFactory;

//     protected $table = 'sucursales';

//     protected $fillable = [
//         'nombre',
//         'direccion',
//         'telefono',
//         'email',
//         'activo'
//     ];
//     public function inventarioSucursalLotes()
//     {
//         return $this->hasMany(InventarioSucursalLote::class, 'sucursal_id');
//     }
//     public function movimientosInventario()
//     {
//         return $this->hasMany(MovimientoInventario::class, 'sucursal_id');
//     }

//     // public function productos()
//     // {
//     //     return $this->hasMany(Producto::class, 'sucursal_id');
//     // }
// }
