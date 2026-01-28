<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    // AÑADIR ESTA LÍNEA
    protected $table = 'historial_acciones'; 

    protected $fillable = ['user_id', 'accion', 'descripcion'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}