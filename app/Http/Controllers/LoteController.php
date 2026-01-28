<?php

// namespace App\Http\Controllers;

// use App\Models\Lote;
// use App\Models\Producto;
// use App\Models\Proveedor;
// use Illuminate\Http\Request;
// use App\Helpers\HistorialHelper; // <-- 1. Importación del Helper

// class LoteController extends Controller
// {
//     public function index()
//     {
//         $lotes = Lote::with(['producto','proveedor'])->orderBy('id','desc')->get();
//         return view('admin.lotes.index', compact('lotes'));
//     }

//     public function create()
//     {
//         $productos = Producto::orderBy('nombre')->get();
//         $proveedores = Proveedor::orderBy('nombre_empresa')->get();
//         return view('admin.lotes.create', compact('productos', 'proveedores'));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'producto_id' => 'required|exists:productos,id',
//             'proveedor_id' => 'required|exists:proveedores,id',
//             'codigo_lote' => 'nullable|string|max:255',
//             'cantidad_actual' => 'required|integer|min:0',
//             'fecha_ingreso' => 'required|date',
//             'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_ingreso',
//             'estado' => 'required|boolean',
//         ]);

//         Lote::create($request->all());
//         //  $lote = Lote::create($request->all());
 

//         return redirect()->route('lotes.index')->with('success', 'Lote creado correctamente');
//     }

//     public function show($id)
//     {
//         $lote = Lote::with(['producto','proveedor'])->findOrFail($id);
//         return view('admin.lotes.show', compact('lote'));
//     }

//     public function edit($id)
//     {
//         $lote = Lote::findOrFail($id);
//         $productos = Producto::orderBy('nombre')->get();
//         $proveedores = Proveedor::orderBy('nombre_empresa')->get();
//         return view('admin.lotes.edit', compact('lote', 'productos', 'proveedores'));
//     }

//     public function update(Request $request, $id)
//     {
//         $lote = Lote::findOrFail($id);

//         $request->validate([
//             'producto_id' => 'required|exists:productos,id',
//             'proveedor_id' => 'required|exists:proveedores,id',
//             'codigo_lote' => 'nullable|string|max:255',
//             'cantidad_actual' => 'required|integer|min:0',
//             'fecha_ingreso' => 'required|date',
//             'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_ingreso',
//             'estado' => 'required|boolean',
//         ]);

//         $lote->update($request->all());

//         return redirect()->route('lotes.index')->with('success', 'Lote actualizado correctamente');
//     }

//     public function destroy($id)
//     {
//         $lote = Lote::findOrFail($id);
//         $lote->delete();
//         return redirect()->route('lotes.index')->with('success', 'Lote eliminado correctamente');
//     }
// }
// <?php
// <?php
// <?php
// <?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Helpers\HistorialHelper;
use Exception;
use Illuminate\Validation\Rule;


class LoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:ver')->only(['index', 'show', 'create', 'edit']);
        $this->middleware('can:crear')->only([ 'store']);
        $this->middleware('can:editar')->only([ 'update']);
        $this->middleware('can:eliminar')->only(['destroy']);
    }

    /**
     * Método centralizado para recalcular y actualizar el stock de un producto
     */
    private function updateProductStock(int $productId): void
    {
        $producto = Producto::find($productId);
        if ($producto) {
            // La lógica CLAVE: Recalcula el stock sumando la cantidad actual de TODOS los lotes
            $producto->stock_actual = $producto->lotes()->sum('cantidad_actual');
            $producto->save();
        }
    }

    public function index()
    {
        $lotes = Lote::with(['producto','proveedor'])->orderBy('id','desc')->get();
        return view('admin.lotes.index', compact('lotes'));
    }

    public function create()
    {
        $productos = Producto::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre_empresa')->get();
        return view('admin.lotes.create', compact('productos', 'proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
           'codigo_lote' => [
    'nullable',
    'string',
    'max:255',
    Rule::unique('lotes')->where(function ($query) use ($request) {
        return $query->where('producto_id', $request->producto_id);
    }),
],

            'cantidad_actual' => 'required|integer|min:0',
            'fecha_ingreso' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_ingreso',
            'estado' => 'required|boolean',
        ]);
        
        try {
            $lote = Lote::create($request->all());
            
            // Llama al método centralizado para actualizar el stock.
            $this->updateProductStock($lote->producto_id); 

            // 🚨 REGISTRO DE HISTORIAL
            $producto = Producto::find($lote->producto_id); 
            $nombre_prod = $producto->nombre ?? 'Desconocido';
            $codigo_lote = $lote->codigo_lote ?? 'N/A';
            $id_lote = $lote->id ?? 'N/A';

            HistorialHelper::registrar(
                "Creación de Lote",
                "Se creó el Lote ID: {$id_lote} ({$codigo_lote}) para **{$nombre_prod}**. Cantidad: {$lote->cantidad_actual}."
            );

            return redirect()->route('lotes.index')
                ->with('mensaje', 'Lote creado correctamente.')
                ->with('icono', 'success');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al crear el lote: ' . $e->getMessage())
                ->with('icono', 'error')
                ->withInput();
        }
    }

    public function show($id)
    {
        $lote = Lote::with(['producto','proveedor'])->findOrFail($id);
        return view('admin.lotes.show', compact('lote'));
    }

    public function edit($id)
    {
        $lote = Lote::findOrFail($id);
        $productos = Producto::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre_empresa')->get();
        return view('admin.lotes.edit', compact('lote', 'productos', 'proveedores'));
    }

    public function update(Request $request, $id)
    {
        $lote = Lote::findOrFail($id);

        $old_producto_id = $lote->producto_id; // Producto original antes de la actualización

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'proveedor_id' => 'required|exists:proveedores,id',
           'codigo_lote' => [
    'nullable',
    'string',
    'max:255',
    Rule::unique('lotes')->where(function ($query) use ($request) {
        return $query->where('producto_id', $request->producto_id);
    })->ignore($id),
],

            'cantidad_actual' => 'required|integer|min:0', 
            'fecha_ingreso' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_ingreso',
            'estado' => 'required|boolean',
        ]);

        try {
            $lote->update($request->all());

            // Si el producto ha cambiado (se movió el lote a otro producto)
            if ($old_producto_id !== $lote->producto_id) {
                // 1. Actualiza el stock del producto anterior
                $this->updateProductStock($old_producto_id); 
            }

            // 2. Actualiza el stock del producto actual (siempre necesario por si cambió la cantidad)
            $this->updateProductStock($lote->producto_id); 

            // 🚨 REGISTRO DE HISTORIAL
            $producto_actual = Producto::find($lote->producto_id);
            $codigo_lote = $lote->codigo_lote ?? 'N/A';
            $id_lote = $lote->id ?? 'N/A';
            $nombre_prod = $producto_actual->nombre ?? 'Desconocido';
            $old_cantidad = $lote->getOriginal('cantidad_actual');
            
            $desc = "Se editó el Lote ID: {$id_lote} ({$codigo_lote}) de **{$nombre_prod}**.";
            if ($old_cantidad != $lote->cantidad_actual) {
                $desc .= " Cantidad ajustada de {$old_cantidad} a {$lote->cantidad_actual}.";
            }
            HistorialHelper::registrar("Edición de Lote", $desc);

            return redirect()->route('lotes.index')
                ->with('mensaje', 'Lote actualizado correctamente.')
                ->with('icono', 'success');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al actualizar el lote: ' . $e->getMessage())
                ->with('icono', 'error')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $lote = Lote::with('producto')->findOrFail($id);
            $productoId = $lote->producto_id; // Guardamos el ID antes de borrar

            $codigo_lote = $lote->codigo_lote ?? 'N/A';
            $cantidad = $lote->cantidad_actual;
            $producto_nombre = $lote->producto ? ($lote->producto->nombre ?? 'Desconocido') : 'Desconocido';
            
            $lote->delete();

            // Llama al método centralizado para actualizar el stock del producto afectado.
            $this->updateProductStock($productoId);
            
            // 🚨 REGISTRO DE HISTORIAL
            HistorialHelper::registrar(
                "Eliminación de Lote",
                "Se eliminó el Lote ID: {$id} ({$codigo_lote}) del producto **{$producto_nombre}**. Cantidad al borrar: {$cantidad}."
            );
            
            return redirect()->route('lotes.index')
                ->with('mensaje', 'Lote eliminado correctamente.')
                ->with('icono', 'success');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al eliminar el lote: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}