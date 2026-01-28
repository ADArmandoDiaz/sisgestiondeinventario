<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Compra;
// use App\Models\Proveedor;
// use App\Models\Producto;
// use App\Models\Sucursal;

// class CompraController extends Controller
// {
//     public function index()
//     {
//         // Logica para mostrar la lista de compras
//         $compras = Compra::all();
//         return view('admin.compras.index', compact('compras'));
//     }
//     public function create()
//     {
//         // Lógica para mostrar el formulario de creación de compra
//         $proveedores = Proveedor::all();
//         $productos = Producto::all();
//         $sucursales = Sucursal::all();
//         return view('admin.compras.create', compact('proveedores', 'productos', 'sucursales'));
//     }

//     public function store(Request $request)
//     {
//         // Lógica para almacenar una nueva compra
//         $request->validate([
//             'proveedor_id' => 'required|exists:proveedores,id',
//             'fecha_compra' => 'required|date',
//             'observaciones' => 'nullable|string',
//         ]);

//      $compra = new Compra();
//         $compra->proveedor_id = $request->input('proveedor_id');
//         $compra->fecha_compra = $request->input('fecha_compra');
//         $compra->observacion = $request->input('observaciones');
//         $compra->total = 0; // Inicialmente el total es 0, se actualizará al agregar productos
//         $compra->estado = 'pendiente'; // Estado inicial
//         $compra->save();

//         return redirect()->route('compras.edit', $compra->id)->with('mensaje', 'Compra creada exitosamente, ahora puede agregar productos')->with('icono', 'success');
//     }

//    public function edit($id)
//     {
//         // Lógica para mostrar el formulario de edición de compra
//         $compra = Compra::findOrFail($id);
//         $proveedores = Proveedor::all();
//         $productos = Producto::all();
//         $sucursales = Sucursal::all();
//         return view('admin.compras.edit', compact('compra', 'proveedores', 'productos', 'sucursales'));
//     }

 
// }
