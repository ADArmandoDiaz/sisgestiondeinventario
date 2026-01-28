<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Movimiento extends Model
{
    protected $fillable = [
        'producto_id',
        'lote_id',      // nuevo campo opcional
        'tipo',         // entrada o salida
        'cantidad',
        'descripcion',
        'usuario_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // 🔹 Lógica de actualización de stock basada en lotes
    protected static function booted()
    {
        static::created(function ($movimiento) {

            $producto = $movimiento->producto;

            if ($movimiento->tipo === 'entrada') {

                // Crear nuevo lote si no se pasó uno específico
                if (!$movimiento->lote_id) {
                    $lote = $producto->lotes()->create([
                        'codigo_lote'      => 'L' . time(),  // ejemplo simple
                        'cantidad_actual'  => $movimiento->cantidad,
                        'fecha_ingreso'    => now(),
                        'fecha_vencimiento'=> null,
                        'estado'           => true,
                    ]);
                    $movimiento->lote_id = $lote->id;
                    $movimiento->save();
                } else {
                    // Sumar cantidad al lote existente
                    $lote = $movimiento->lote;
                    $lote->cantidad_actual += $movimiento->cantidad;
                    $lote->save();
                }

            } else { // Salida
                $cantidadRestante = $movimiento->cantidad;

                // FIFO: tomar lotes activos más antiguos primero
                $lotes = $producto->lotes()->where('estado', true)
                            ->orderBy('fecha_ingreso', 'asc')
                            ->get();

                foreach ($lotes as $lote) {
                    if ($cantidadRestante <= 0) break;

                    if ($lote->cantidad_actual >= $cantidadRestante) {
                        $lote->cantidad_actual -= $cantidadRestante;
                        $cantidadRestante = 0;
                    } else {
                        $cantidadRestante -= $lote->cantidad_actual;
                        $lote->cantidad_actual = 0;
                    }

                    // Si el lote queda en 0, opcional: marcar inactivo
                    if ($lote->cantidad_actual == 0) {
                        $lote->estado = false;
                    }

                    $lote->save();
                }

                if ($cantidadRestante > 0) {
                    throw new \Exception("Stock insuficiente en los lotes para realizar esta salida.");
                }
            }

            // 🔹 Actualizar stock_actual del producto sumando todos los lotes activos
            $producto->stock_actual = $producto->lotes()->where('estado', true)->sum('cantidad_actual');
            $producto->save();
        });
    }
    
}