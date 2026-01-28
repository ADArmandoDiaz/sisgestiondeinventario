<?php

// namespace App\Http\Controllers;

// use App\Models\Movimiento;
// use App\Models\Producto;
// use App\Models\Proveedor;
// use Illuminate\Http\Request;
// use Exception;
// use Illuminate\Support\Facades\Auth;
// use App\Helpers\HistorialHelper; // <--- ¡IMPORTAR EL HELPER!

// class MovimientoController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth');

//         // Permisos por acción (ajústalos según tus permisos definidos)
//         $this->middleware('can:ver')->only(['index', 'show']);
//         $this->middleware('can:crear')->only(['create', 'store']);
//         $this->middleware('can:editar')->only(['edit', 'update']);
//         $this->middleware('can:eliminar')->only(['destroy']);
//     }

//     public function index()
//     {
//         $movimientos = Movimiento::with('producto')->latest()->paginate(10);
//         return view('admin.movimientos.index', compact('movimientos'));
//     }

//     public function create()
// {
//     $productos = Producto::with('lotes')->orderBy('nombre')->get();
//     $proveedores = Proveedor::orderBy('nombre_empresa')->get();

//     return view('admin.movimientos.create', compact('productos', 'proveedores'));
// }


//    public function store(Request $request)
// {
//     $request->validate([
//         'producto_id' => 'required|exists:productos,id',
//         'tipo'        => 'required|in:entrada,salida',
//         'cantidad'    => 'required|integer|min:1',
//         'lote_id'     => 'nullable|exists:lotes,id',
//         'descripcion' => 'nullable|string',
//         'proveedor_id' => [
//                 'nullable',
//                 // Validación: Requerido si es entrada Y no hay lote seleccionado
//                 function ($attribute, $value, $fail) use ($request) {
//                     if ($request->tipo === 'entrada' && empty($request->lote_id) && empty($value)) {
//                         $fail('El proveedor es obligatorio para registrar un nuevo lote.');
//                     }
//                 },
//                 'exists:proveedores,id'
//             ],
//         'fecha_vencimiento' => 'nullable|date',

//     ]);
    

//     try {
//         $producto = Producto::findOrFail($request->producto_id);
//         $cantidad = $request->cantidad;

//         if ($request->tipo === 'entrada') {
//               if ($request->lote_id) {
//                     // Entrada a Lote Existente
//                     $lote = $producto->lotes()->findOrFail($request->lote_id);
//                     $lote->cantidad_actual += $cantidad;
//                     $lote->save();
//                 } else {
//                     // CREACIÓN DE LOTE NUEVO
//                     // 1. Quitamos 'cantidad_inicial' porque no existe en la BD.
//                     // 2. Usamos 'cantidad_actual' como el stock inicial.
//                     $lote = $producto->lotes()->create([
//                         'codigo_lote'       => 'L' . time(),
//                         'cantidad_actual'   => $cantidad, // Aquí se guarda el stock inicial
//                         'fecha_ingreso'     => now(),
//                         'fecha_vencimiento' => $request->fecha_vencimiento,
//                         'proveedor_id'      => $request->proveedor_id,
//                         'estado'            => true,
//                     ]);
//                 }

//         }  else {
//                 // SALIDA (FIFO)
//                 $totalStock = $producto->lotes()->sum('cantidad_actual');
//                 if ($cantidad > $totalStock) {
//                     throw new Exception("No hay suficiente stock disponible para esta salida.");
//                 }

//                 $lotes = $producto->lotes()
//                     ->where('cantidad_actual', '>', 0)
//                     ->orderBy('fecha_ingreso')
//                     ->get();

//                 foreach ($lotes as $loteItem) {
//                     if ($cantidad <= 0) break;

//                     if ($loteItem->cantidad_actual >= $cantidad) {
//                         $loteItem->cantidad_actual -= $cantidad;
//                         $loteItem->save();
//                         $cantidad = 0;
//                     } else {
//                         $cantidad -= $loteItem->cantidad_actual;
//                         $loteItem->cantidad_actual = 0;
//                         $loteItem->save();
//                     }
//                 }
//             }

//        // Determinar ID del lote para el historial de movimientos
//             // Si fue entrada nueva, usamos el ID del lote recién creado ($lote->id)
//             // Si fue entrada a existente o salida, usamos $request->lote_id
//             $loteIdParaMovimiento = ($lote) ? $lote->id : ($request->lote_id ?? null);

//             Movimiento::create([
//                 'producto_id' => $producto->id,
//                 'lote_id' 	  => $loteIdParaMovimiento,
//                 'tipo' 		  => $request->tipo,
//                 'cantidad' 	  => $request->cantidad,
//                 'descripcion' => $request->descripcion,
//                 'usuario_id'  => Auth::id(),
//             ]);

//         // Actualizar stock_actual total del producto
//         $producto->stock_actual = $producto->lotes()->sum('cantidad_actual');
//         $producto->save();
//         // 🚨 REGISTRO DE HISTORIAL (AQUÍ)
//         $accion = $request->tipo === 'entrada' ? "Entrada de Stock" : "Salida de Stock";
//         $desc = "Producto: **{$producto->nombre}** | Tipo: {$request->tipo} | Cantidad: {$request->cantidad}. Lote afectado: {$loteIdParaMovimiento}.";

//         HistorialHelper::registrar($accion, $desc);
//         // FIN REGISTRO DE HISTORIAL

//         return redirect()->route('admin.movimientos.index')
//             ->with('mensaje', 'Movimiento registrado correctamente')
//             ->with('icono', 'success');

//     } catch (Exception $e) {
//         return redirect()->back()
//             ->with('mensaje', $e->getMessage())
//             ->with('icono', 'error')
//             ->withInput();
//     }
// }

//     public function show($id)
//     {
//       $productos = Producto::with('lotes')->orderBy('nombre')->get();
//     $proveedores = Proveedor::orderBy('nombre_empresa')->get();
//     $movimiento = Movimiento::findOrFail($id);

//     return view('admin.movimientos.show', compact('movimiento','productos', 'proveedores', ));
//     }

//     public function edit($id)
//     {
//     $productos = Producto::with('lotes')->orderBy('nombre')->get();
//     $proveedores = Proveedor::orderBy('nombre_empresa')->get();
//     $movimiento = Movimiento::findOrFail($id);

//     return view('admin.movimientos.edit', compact('movimiento','productos', 'proveedores', ));
      
//     }

//     public function update(Request $request, $id)
// {
//     $request->validate([
//         'producto_id' => 'required|exists:productos,id',
//         'tipo'        => 'required|in:entrada,salida',
//         'cantidad'    => 'required|integer|min:1',
//         'lote_id'     => 'nullable|exists:lotes,id',
//         'descripcion' => 'nullable|string',
//     ]);

//     try {
//         $movimiento = Movimiento::findOrFail($id);
//         $productoOld = $movimiento->producto;
//         $cantidadOld = $movimiento->cantidad;
//         $tipoOld = $movimiento->tipo;

//         // 1️⃣ Revertir los efectos en los lotes del movimiento antiguo
//         if ($tipoOld === 'entrada') {
//             // Restamos la cantidad del lote original
//             if ($movimiento->lote_id) {
//                 $lote = $productoOld->lotes()->find($movimiento->lote_id);
//                 if ($lote) {
//                     $lote->cantidad_actual -= $cantidadOld;
//                     $lote->save();
//                 }
//             }
//         } else {
//             // Salida: sumamos cantidad a los lotes antiguos (FIFO inverso simple)
//             $lotes = $productoOld->lotes()->orderBy('fecha_ingreso')->get();
//             $rest = $cantidadOld;
//             foreach ($lotes as $lote) {
//                 $espacio = $cantidadOld - $rest;
//                 $lote->cantidad_actual += $rest; // simple: devolvemos al stock
//                 $lote->save();
//                 $rest -= $rest;
//                 if ($rest <= 0) break;
//             }
//         }

//         // 2️⃣ Aplicar el movimiento actualizado (igual que store)
//         $producto = Producto::findOrFail($request->producto_id);
//         $cantidad = $request->cantidad;

//         if ($request->tipo === 'entrada') {
//             $lote = $request->lote_id 
//                 ? $producto->lotes()->findOrFail($request->lote_id)
//               : $producto->lotes()->create([
//                     'codigo_lote' => 'L' . time(),
//                     // 🚨 CORRECCIÓN: Añadir campos requeridos
//                     'cantidad_inicial' => $cantidad,
//                     'cantidad_actual' => $cantidad,
//                     'fecha_ingreso' => now(),
//                     'fecha_vencimiento' => $request->fecha_vencimiento,
//                     'proveedor_id' => $request->proveedor_id,
//                     'estado' => true
//                 ]);
            
//             // Si se usó un lote existente, actualizamos su cantidad.
//             if ($request->lote_id) {
//                 $lote->cantidad_actual += $cantidad;
//                 $lote->save();
//             }
//         } else {
//             $totalStock = $producto->lotes()->sum('cantidad_actual');
//             if ($cantidad > $totalStock) {
//                 throw new Exception("No hay suficiente stock disponible para esta salida.");
//             }

//             $lotes = $producto->lotes()->where('cantidad_actual', '>', 0)->orderBy('fecha_ingreso')->get();
//             foreach ($lotes as $lote) {
//                 if ($cantidad <= 0) break;

//                 if ($lote->cantidad_actual >= $cantidad) {
//                     $lote->cantidad_actual -= $cantidad;
//                     $lote->save();
//                     $cantidad = 0;
//                 } else {
//                     $cantidad -= $lote->cantidad_actual;
//                     $lote->cantidad_actual = 0;
//                     $lote->save();
//                 }
//             }
//         }

//         // 3️⃣ Actualizar movimiento y stock total
//         $movimiento->update([
//             'producto_id' => $request->producto_id,
//             'lote_id'     => $request->lote_id ?? null,
//             'tipo'        => $request->tipo,
//             'cantidad'    => $request->cantidad,
//             'descripcion' => $request->descripcion,
//         ]);

//         $producto->stock_actual = $producto->lotes()->sum('cantidad_actual');
//         $producto->save();
//         // 🚨 REGISTRO DE HISTORIAL (AQUÍ)
//         $accion = "Edición de Movimiento de Inventario";
//         $desc = "Se editó el Movimiento ID: {$movimiento->id}. Cantidad original: {$cantidadOld}, Tipo original: {$tipoOld}. Nuevo: {$request->tipo} / {$request->cantidad}.";
        
//         HistorialHelper::registrar($accion, $desc);
//         // FIN REGISTRO DE HISTORIAL

//         return redirect()->route('admin.movimientos.index')
//             ->with('mensaje', 'Movimiento actualizado correctamente')
//             ->with('icono', 'success');

//     } catch (Exception $e) {
//         return redirect()->back()
//             ->with('mensaje', $e->getMessage())
//             ->with('icono', 'error')
//             ->withInput();
//     }
// }

//    public function destroy($id)
// {
//     try {
//         $movimiento = Movimiento::findOrFail($id);
//         $producto = $movimiento->producto;

//         // Revertir los efectos en los lotes antes de eliminar
//         if ($movimiento->tipo === 'entrada') {
//             if ($movimiento->lote_id) {
//                 $lote = $producto->lotes()->find($movimiento->lote_id);
//                 if ($lote) {
//                     $lote->cantidad_actual -= $movimiento->cantidad;
//                     $lote->save();
//                 }
//             }
//         } else {
//             // Salida: devolvemos al stock usando FIFO inverso simple
//             $lotes = $producto->lotes()->orderBy('fecha_ingreso')->get();
//             $rest = $movimiento->cantidad;
//             foreach ($lotes as $lote) {
//                 $lote->cantidad_actual += $rest; // simple
//                 $lote->save();
//                 $rest -= $rest;
//                 if ($rest <= 0) break;
//             }
//         }
//         // Guardar datos antes de eliminar para el historial
//         $tipoEliminado = $movimiento->tipo;
//         $cantidadEliminada = $movimiento->cantidad;
//         $productoNombre = $producto->nombre;

//         // Actualizamos stock total y eliminamos
//         $producto->stock_actual = $producto->lotes()->sum('cantidad_actual');
//         $producto->save();

//         $movimiento->delete();

//         // 🚨 REGISTRO DE HISTORIAL (AQUÍ)
//         $accion = "Eliminación de Movimiento de Inventario";
//         $desc = "Se eliminó el Movimiento ID: {$id}. Revirtió {$cantidadEliminada} de tipo {$tipoEliminado} del producto **{$productoNombre}**.";
        
//         HistorialHelper::registrar($accion, $desc);
//         // FIN REGISTRO DE HISTORIAL

//         return redirect()->route('admin.movimientos.index')
//             ->with('mensaje', 'Movimiento eliminado correctamente')
//             ->with('icono', 'success');

//     } catch (Exception $e) {
//         return redirect()->back()
//             ->with('mensaje', $e->getMessage())
//             ->with('icono', 'error');
//     }
// }
// }

// <?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Lote; // <-- ¡Importar el modelo Lote!
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Helpers\HistorialHelper;

class MovimientoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Permisos por acción (ajústalos según tus permisos definidos)
        $this->middleware('can:ver')->only(['index', 'show', 'create', 'edit']);
        $this->middleware('can:crear')->only([ 'store']);
        $this->middleware('can:editar')->only([ 'update']);
        $this->middleware('can:eliminar')->only(['destroy']);
    }

    /**
     * Método centralizado para recalcular y actualizar el stock de un producto
     *
     * @param int $productId El ID del producto cuyo stock debe recalcularse.
     */
    private function updateProductStock(int $productId): void
    {
        $producto = Producto::find($productId);
        if ($producto) {
            // La lógica CLAVE: Recalcula el stock sumando la cantidad actual de TODOS los lotes.
            $producto->stock_actual = $producto->lotes()->sum('cantidad_actual');
            $producto->save();
        }
    }

    public function index()
    {
        $movimientos = Movimiento::with('producto')->latest()->paginate(10);
        return view('admin.movimientos.index', compact('movimientos'));
    }

    public function create()
    {
        $productos = Producto::with('lotes')->orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre_empresa')->get();

        return view('admin.movimientos.create', compact('productos', 'proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo'        => 'required|in:entrada,salida',
            'cantidad'    => 'required|integer|min:1',
            'lote_id'     => 'nullable|exists:lotes,id',
            'descripcion' => 'nullable|string',
            'proveedor_id' => [
                'nullable',
                // Validación: Requerido si es entrada Y no hay lote seleccionado
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo === 'entrada' && empty($request->lote_id) && empty($value)) {
                        $fail('El proveedor es obligatorio para registrar un nuevo lote.');
                    }
                },
                'exists:proveedores,id'
            ],
            'fecha_vencimiento' => 'nullable|date',

        ]);

       $lote = null;

    try {
        $producto = Producto::findOrFail($request->producto_id);
        $cantidad = $request->cantidad;

        // 1. BLOQUE DE LÓGICA DE LOTES (Sin cambios, esto está bien)
        Lote::withoutEvents(function () use ($request, $producto, $cantidad, &$lote) {
            if ($request->tipo === 'entrada') {
                if ($request->lote_id) {
                    $lote = $producto->lotes()->findOrFail($request->lote_id);
                    $lote->cantidad_actual += $cantidad;
                    $lote->save();
                } else {
                    $lote = $producto->lotes()->create([
                        'codigo_lote'     => 'L' . time(),
                        'cantidad_actual' => $cantidad,
                        'fecha_ingreso'   => now(),
                        'fecha_vencimiento' => $request->fecha_vencimiento,
                        'proveedor_id'    => $request->proveedor_id,
                        'estado'          => true,
                    ]);
                }
            } else {
                // SALIDA (FIFO)
                $totalStock = $producto->lotes()->sum('cantidad_actual');
                if ($cantidad > $totalStock) {
                    throw new Exception("No hay suficiente stock disponible para esta salida.");
                }

                $lotes = $producto->lotes()
                    ->where('cantidad_actual', '>', 0)
                    ->orderBy('fecha_ingreso')
                    ->get();

                foreach ($lotes as $loteItem) {
                    if ($cantidad <= 0) break;

                    if ($loteItem->cantidad_actual >= $cantidad) {
                        $loteItem->cantidad_actual -= $cantidad;
                        $loteItem->save();
                        $cantidad = 0;
                    } else {
                        $cantidad -= $loteItem->cantidad_actual;
                        $loteItem->cantidad_actual = 0;
                        $loteItem->save();
                    }
                }
            }
        });

        // Determinar ID del lote
        $loteIdParaMovimiento = ($lote) ? $lote->id : ($request->lote_id ?? null);

        // 🔥 SOLUCIÓN AQUÍ: Usamos Movimiento::withoutEvents
        // Esto previene que cualquier Observer duplique la suma/resta
        Movimiento::withoutEvents(function () use ($producto, $loteIdParaMovimiento, $request) {
            Movimiento::create([
                'producto_id' => $producto->id,
                'lote_id'     => $loteIdParaMovimiento,
                'tipo'        => $request->tipo,
                'cantidad'    => $request->cantidad,
                'descripcion' => $request->descripcion,
                'usuario_id'  => Auth::id(),
            ]);
        });

        // 3. ACTUALIZACIÓN FINAL DE STOCK
        $this->updateProductStock($producto->id);

        // ... (Tu código de Historial y Return se mantiene igual) ...
        
        // (Solo para completar el ejemplo visual)
        HistorialHelper::registrar($request->tipo === 'entrada' ? "Entrada" : "Salida", "Movimiento registrado");

        return redirect()->route('admin.movimientos.index')
            ->with('mensaje', 'Movimiento registrado correctamente')
            ->with('icono', 'success');

    } catch (Exception $e) {
        return redirect()->back()->with('mensaje', $e->getMessage())->with('icono', 'error')->withInput();
    }
    }

    public function show($id)
    {
        $productos = Producto::with('lotes')->orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre_empresa')->get();
        $movimiento = Movimiento::findOrFail($id);

        return view('admin.movimientos.show', compact('movimiento','productos', 'proveedores', ));
    }

    public function edit($id)
    {
        $productos = Producto::with('lotes')->orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre_empresa')->get();
        $movimiento = Movimiento::findOrFail($id);

        return view('admin.movimientos.edit', compact('movimiento','productos', 'proveedores', ));

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo'        => 'required|in:entrada,salida',
            'cantidad'    => 'required|integer|min:1',
            'lote_id'     => 'nullable|exists:lotes,id',
            'descripcion' => 'nullable|string',
            // ... (Agregar validación de proveedor y vencimiento si aplica para edición)
        ]);

        try {
            $movimiento = Movimiento::findOrFail($id);
            $productoOld = $movimiento->producto;
            $cantidadOld = $movimiento->cantidad;
            $tipoOld = $movimiento->tipo;

            // 🔥 SOLUCIÓN: Usamos withoutEvents para la reversión y la nueva aplicación
            Lote::withoutEvents(function () use ($movimiento, $productoOld, $cantidadOld, $tipoOld, $request) {
                
                // 1️⃣ Revertir los efectos en los lotes del movimiento antiguo
                if ($tipoOld === 'entrada') {
                    // Restamos la cantidad del lote original
                    if ($movimiento->lote_id) {
                        $lote = $productoOld->lotes()->find($movimiento->lote_id);
                        if ($lote) {
                            $lote->cantidad_actual -= $cantidadOld;
                            $lote->save();
                        }
                    }
                } else {
                    // Salida: sumamos cantidad a los lotes antiguos (FIFO inverso simple)
                    $lotes = $productoOld->lotes()->orderBy('fecha_ingreso')->get();
                    $rest = $cantidadOld;
                    foreach ($lotes as $lote) {
                        $lote->cantidad_actual += $rest; // simple: devolvemos al stock
                        $lote->save();
                        $rest = 0; 
                        if ($rest <= 0) break;
                    }
                }
            });
            // ⚠️ Llamar a la actualización de stock para el producto original
            $this->updateProductStock($productoOld->id);

            $producto = Producto::findOrFail($request->producto_id);
            $cantidad = $request->cantidad;
            $lote = null; // Inicializar para la nueva aplicación

            // 🔥 SOLUCIÓN: Usamos withoutEvents para la nueva aplicación del movimiento
            Lote::withoutEvents(function () use ($request, $producto, $cantidad, &$lote) {
                // 2️⃣ Aplicar el movimiento actualizado (igual que store)
                if ($request->tipo === 'entrada') {
                    // Si el movimiento es de entrada, aseguramos que se use un lote
                    if ($request->lote_id) {
                        $lote = $producto->lotes()->findOrFail($request->lote_id);
                        $lote->cantidad_actual += $cantidad;
                        $lote->save();
                    } else {
                        // Creación de lote (si el formulario lo permite)
                        $lote = $producto->lotes()->create([
                            'codigo_lote' => 'L' . time(),
                            'cantidad_actual' => $cantidad, 
                            'fecha_ingreso' => now(),
                            'fecha_vencimiento' => $request->fecha_vencimiento,
                            'proveedor_id' => $request->proveedor_id,
                            'estado' => true
                        ]);
                    }
                    
                } else {
                    // Salida (FIFO)
                    $totalStock = $producto->lotes()->sum('cantidad_actual');
                    if ($cantidad > $totalStock) {
                        throw new Exception("No hay suficiente stock disponible para esta salida.");
                    }

                    $lotesAfectados = $producto->lotes()->where('cantidad_actual', '>', 0)->orderBy('fecha_ingreso')->get();
                    $cantidadPendiente = $cantidad;
                    
                    foreach ($lotesAfectados as $loteItem) {
                        if ($cantidadPendiente <= 0) break;

                        if ($loteItem->cantidad_actual >= $cantidadPendiente) {
                            $loteItem->cantidad_actual -= $cantidadPendiente;
                            $loteItem->save();
                            $cantidadPendiente = 0;
                        } else {
                            $cantidadPendiente -= $loteItem->cantidad_actual;
                            $loteItem->cantidad_actual = 0;
                            $loteItem->save();
                        }
                    }
                }
            });
            // 🔥 Fin withoutEvents

            // 3️⃣ Actualizar movimiento y stock total
            $movimiento->update([
                'producto_id' => $request->producto_id,
                'lote_id'     => $request->lote_id ?? ($lote ? $lote->id : null),
                'tipo'        => $request->tipo,
                'cantidad'    => $request->cantidad,
                'descripcion' => $request->descripcion,
            ]);

            // 🔥 ACTUALIZACIÓN CLAVE: Usa el método centralizado, ¡AHORA ES LA ÚNICA FUENTE!
            $this->updateProductStock($producto->id);

            // 🚨 REGISTRO DE HISTORIAL (AQUÍ)
            $accion = "Edición de Movimiento de Inventario";
            $desc = "Se editó el Movimiento ID: {$movimiento->id}. Producto: {$producto->nombre}. Cantidad original: {$cantidadOld}, Tipo original: {$tipoOld}. Nuevo: {$request->tipo} / {$request->cantidad}.";

            HistorialHelper::registrar($accion, $desc);
            // FIN REGISTRO DE HISTORIAL

            return redirect()->route('admin.movimientos.index')
                ->with('mensaje', 'Movimiento actualizado correctamente')
                ->with('icono', 'success');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', $e->getMessage())
                ->with('icono', 'error')
                ->withInput();
        }
    }

    public function destroy($id)
{
    try {
        $movimiento = Movimiento::findOrFail($id);
        $producto = $movimiento->producto;

        // 1. REVERSIÓN DE LOTES
        Lote::withoutEvents(function () use ($movimiento, $producto) {
            
            // Si el movimiento era una ENTRADA, al eliminarlo debemos RESTAR del lote
            if ($movimiento->tipo === 'entrada') {
                if ($movimiento->lote_id) {
                    $lote = $producto->lotes()->find($movimiento->lote_id);
                    // Solo restamos si el lote aun existe y tiene cantidad suficiente
                    // (Opcional: puedes permitir que quede en negativo si es necesario auditar)
                    if ($lote) {
                        $lote->cantidad_actual -= $movimiento->cantidad;
                        // Evitar negativos si prefieres
                        if($lote->cantidad_actual < 0) $lote->cantidad_actual = 0; 
                        $lote->save();
                    }
                }
            } 
            // Si el movimiento era una SALIDA, al eliminarlo debemos DEVOLVER (SUMAR) al stock
            else {
                // Lo ideal sería devolverlo al lote original, pero si no sabemos cual fue (porque FIFO afecta varios),
                // lo devolvemos al lote más antiguo o al lote prioritario.
                
                // Opción A: Intentar devolver al lote original si existe
                $loteOriginal = $movimiento->lote_id ? $producto->lotes()->find($movimiento->lote_id) : null;
                
                if ($loteOriginal) {
                    $loteOriginal->cantidad_actual += $movimiento->cantidad;
                    $loteOriginal->save();
                } else {
                    // Opción B (Fallback): Si no hay lote ID registrado, devolvemos al lote activo más antiguo
                    // O creamos uno de "Devolución"
                    $lote = $producto->lotes()->orderBy('fecha_ingreso', 'asc')->first();
                    if ($lote) {
                        $lote->cantidad_actual += $movimiento->cantidad;
                        $lote->save();
                    }
                }
            }
        });

        // Guardar datos para historial
        $datosHistorial = [
            'tipo' => $movimiento->tipo,
            'cantidad' => $movimiento->cantidad,
            'producto' => $producto->nombre
        ];

        // 🔥 SOLUCIÓN AQUÍ: Usamos withoutEvents al eliminar el movimiento
        Movimiento::withoutEvents(function () use ($movimiento) {
            $movimiento->delete();
        });

        // 3. RECALCULAR STOCK FINAL (La fuente de la verdad)
        $this->updateProductStock($producto->id);

        // Registro historial...
        HistorialHelper::registrar("Eliminación", "Se revirtió {$datosHistorial['cantidad']} de {$datosHistorial['producto']}");

        return redirect()->route('admin.movimientos.index')
            ->with('mensaje', 'Movimiento eliminado y stock revertido correctamente')
            ->with('icono', 'success');

    } catch (Exception $e) {
        return redirect()->back()->with('mensaje', $e->getMessage())->with('icono', 'error');
    }
}
}