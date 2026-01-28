<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /** @use HasFactory<\Database\Factories\ProveedorFactory> */
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre_empresa',
        'contacto_nombre',
        'contacto_telefono',
        'contacto_email',
        'direccion',
       
    ];
    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }
    // public function compras()
    // {
    //     return $this->hasMany(Compra::class);
    // }
}
