<?php

// namespace App\Http\Controllers;

// use App\Models\Producto;
// use App\Models\Categoria;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
// use PHPUnit\Framework\MockObject\Stub\ReturnReference;

// use function Symfony\Component\String\s;

// class ProductoController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//    public function __construct()
// {
//     // Middleware general para que solo usuarios autenticados puedan acceder
//     $this->middleware('auth');

//     // Permisos por acción
//     $this->middleware('can:ver')->only(['index', 'show']);
//     $this->middleware('can:crear')->only(['create', 'store']);
//     $this->middleware('can:editar')->only(['edit', 'update']);
//     $this->middleware('can:eliminar')->only(['destroy']);
// }
//     public function index()
//     {
//         $productos = Producto::all();
//          $categorias = Categoria::all(); // 👈 FILTRO DE CATEGORÍAS
//         // return response()->json($productos);
//         // $productos = Producto::all();
//           return view('admin.productos.index', compact('productos', 'categorias'));
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         $categorias = Categoria::all();
//         return view('admin.productos.create', compact('categorias'));
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//  $request->validate([
//             'categoria_id' => 'required|exists:categorias,id',
//             'codigo' => 'required|unique:productos,codigo',
//             'nombre' => 'required|unique:productos,nombre',
//             'description' => 'required',
//             'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//             'precio_compra' => 'required|numeric',
//             'precio_venta' => 'required|numeric',
//             'stock_minimo' => 'required|integer',
//             'stock_maximo' => 'required|integer ',
//             'unidad_medida' => 'required',
//             'estado' => 'required|boolean',
//         ]);
//         $producto = new Producto();
//         $producto->categoria_id = $request->categoria_id;
//         $producto->codigo = $request->codigo;
//         $producto->nombre = $request->nombre;
//         $producto->description = $request->description;
//         $producto->imagen = $request->file('imagen')->store('imagenes/productos', 'public');
//         $producto->precio_compra = $request->precio_compra;
//         $producto->precio_venta = $request->precio_venta;
//         $producto->stock_minimo = $request->stock_minimo;
//         $producto->stock_maximo = $request->stock_maximo;
//         $producto->unidad_medida = $request->unidad_medida;
//         $producto->estado = $request->estado;
//         $producto->save();

//         return redirect()->route('productos.index')
//         ->with('mensaje', 'Producto creado exitosamente.')
//         ->with('icono', 'success');
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show($id)
//     {
//         // echo "Mostrando el producto con ID: " . $id;
//         $producto = Producto::findOrFail($id);
//         return view('admin.productos.show', compact('producto'));
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit($id)
//     {
//         $producto = Producto::findOrFail($id);
//         $categorias = Categoria::all();
//         return view('admin.productos.edit', compact('producto', 'categorias'));
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request,$id)
//     {
//     // return response()->json($request->all());
//         $request->validate([
//             'categoria_id' => 'required|exists:categorias,id',
//             'codigo' => 'required|unique:productos,codigo,'. $id,
//             'nombre' => 'required|unique:productos,nombre,'. $id,
//             'description' => 'required',
//             'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//             'precio_compra' => 'required|numeric',
//             'precio_venta' => 'required|numeric',
//             'stock_minimo' => 'required|integer',
//             'stock_maximo' => 'required|integer ',
//             'unidad_medida' => 'required',
//             'estado' => 'required|boolean',
//         ]);
//         $producto = Producto::findOrFail($id);
//         $producto->categoria_id = $request->categoria_id;
//         $producto->codigo = $request->codigo;
//         $producto->nombre = $request->nombre;
//         $producto->description = $request->description;

//         if ($request->hasFile('imagen')) {
//             Storage::disk('public')->delete($producto->imagen);
//             $producto->imagen = $request->file('imagen')->store('imagenes/productos', 'public');
//         }

//         $producto->precio_compra = $request->precio_compra;
//         $producto->precio_venta = $request->precio_venta;
//         $producto->stock_minimo = $request->stock_minimo;
//         $producto->stock_maximo = $request->stock_maximo;
//         $producto->unidad_medida = $request->unidad_medida;
//         $producto->estado = $request->estado;
//         $producto->save();

//         return redirect()->route('productos.index')
//             ->with('mensaje', 'Producto actualizado exitosamente.')
//             ->with('icono', 'success');
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy($id)
//     {
//         $producto = Producto::findOrFail($id);
//         Storage::disk('public')->delete($producto->imagen);
//         $producto->delete();

//         return redirect()->route('productos.index')
//             ->with('mensaje', 'Producto eliminado exitosamente.')
//             ->with('icono', 'success');
//     }
// }

// <?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\HistorialHelper; // <-- Agregado para registro de historial
use Exception; // <-- Agregado para manejo de excepciones
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

use function Symfony\Component\String\s;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        // Middleware general para que solo usuarios autenticados puedan acceder
        $this->middleware('auth');

        // Permisos por acción
        $this->middleware('can:ver')->only(['index', 'show','create', 'edit']);
        $this->middleware('can:crear')->only([ 'store']);
        $this->middleware('can:editar')->only([ 'update']);
        $this->middleware('can:eliminar')->only(['destroy']);
    }
    
    public function index()
    {
        $productos = Producto::all();
        $categorias = Categoria::all(); // 👈 FILTRO DE CATEGORÍAS
        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'codigo' => 'required|unique:productos,codigo',
            'nombre' => 'required|unique:productos,nombre',
            'description' => 'required',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock_minimo' => 'required|integer',
            'stock_maximo' => 'required|integer ',
            'unidad_medida' => 'required',
            'estado' => 'required|boolean',
        ]);

        try {
            $producto = new Producto();
            $producto->categoria_id = $request->categoria_id;
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->description = $request->description;
            
            // Subir imagen
            $producto->imagen = $request->file('imagen')->store('imagenes/productos', 'public');
            
            $producto->precio_compra = $request->precio_compra;
            $producto->precio_venta = $request->precio_venta;
            $producto->stock_minimo = $request->stock_minimo;
            $producto->stock_maximo = $request->stock_maximo;
            // Inicializar stock_actual a 0 ya que se gestiona por Lotes
            $producto->stock_actual = 0; 
            $producto->unidad_medida = $request->unidad_medida;
            $producto->estado = $request->estado;
            $producto->save();

            // 🚨 REGISTRO DE HISTORIAL
            HistorialHelper::registrar(
                "Creación de Producto",
                "Se creó el producto **{$producto->nombre}** (Cód: {$producto->codigo}, ID: {$producto->id})."
            );
            
            return redirect()->route('productos.index')
                ->with('mensaje', 'Producto creado exitosamente.')
                ->with('icono', 'success');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al crear el producto: ' . $e->getMessage())
                ->with('icono', 'error')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('admin.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'codigo' => 'required|unique:productos,codigo,'. $id,
            'nombre' => 'required|unique:productos,nombre,'. $id,
            'description' => 'required',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'stock_minimo' => 'required|integer',
            'stock_maximo' => 'required|integer ',
            'unidad_medida' => 'required',
            'estado' => 'required|boolean',
        ]);
        
        try {
            $producto = Producto::findOrFail($id);
            $old_nombre = $producto->nombre;
            $old_codigo = $producto->codigo;

            $producto->categoria_id = $request->categoria_id;
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->description = $request->description;

            $log_changes = [];

            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior y guardar la nueva
                Storage::disk('public')->delete($producto->imagen);
                $producto->imagen = $request->file('imagen')->store('imagenes/productos', 'public');
                $log_changes[] = "Imagen actualizada";
            }
            
            $producto->precio_compra = $request->precio_compra;
            $producto->precio_venta = $request->precio_venta;
            $producto->stock_minimo = $request->stock_minimo;
            $producto->stock_maximo = $request->stock_maximo;
            $producto->unidad_medida = $request->unidad_medida;
            $producto->estado = $request->estado;
            $producto->save();

            // 🚨 REGISTRO DE HISTORIAL
            if ($old_nombre != $producto->nombre) { $log_changes[] = "Nombre '{$old_nombre}' -> '{$producto->nombre}'"; }
            if ($old_codigo != $producto->codigo) { $log_changes[] = "Código '{$old_codigo}' -> '{$producto->codigo}'"; }

            HistorialHelper::registrar(
                "Edición de Producto",
                "Se actualizó el producto **{$producto->nombre}** (ID: {$id}). Cambios: " . (implode(', ', $log_changes) ?: "Otros datos.")
            );

            return redirect()->route('productos.index')
                ->with('mensaje', 'Producto actualizado exitosamente.')
                ->with('icono', 'success');
        
        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al actualizar el producto: ' . $e->getMessage())
                ->with('icono', 'error')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $nombre_producto = $producto->nombre;
            $imagen_path = $producto->imagen;

            // Eliminar imagen antes de eliminar registro de DB
            Storage::disk('public')->delete($imagen_path);
            $producto->delete();

            // 🚨 REGISTRO DE HISTORIAL
            HistorialHelper::registrar(
                "Eliminación de Producto",
                "Se eliminó permanentemente el producto **{$nombre_producto}** (ID: {$id})."
            );

            return redirect()->route('productos.index')
                ->with('mensaje', 'Producto eliminado exitosamente.')
                ->with('icono', 'success');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al eliminar el producto: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}