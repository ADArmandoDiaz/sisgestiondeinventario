<?php

namespace App\Helpers;

use App\Models\Historial;
use Illuminate\Support\Facades\Auth;

class HistorialHelper
{
    public static function registrar($accion, $descripcion = null)
    {
        Historial::create([
            'user_id' => Auth::id(),
            'accion' => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
