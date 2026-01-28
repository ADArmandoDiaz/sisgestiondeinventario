<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Helpers\HistorialHelper; // Agregado para registro de historial
use Exception; // Agregado para manejo de excepciones
use Illuminate\Support\Facades\Validator; // Mantener si se necesita para validación manual avanzada

class ProveedorController extends Controller
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
        $proveedores = Proveedor::all();
        return view('admin.proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirige a la vista de creación
        return view('admin.proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_empresa' => 'required|string|max:255|unique:proveedores,nombre_empresa', // Añadida unicidad
            'contacto_nombre' => 'required|string|max:255',
            'contacto_telefono' => 'required|string|max:20',
            'contacto_email' => 'required|email|max:255|unique:proveedores,contacto_email',
            'direccion' => 'required|string|max:500',
        ]);
    
        try {
            $proveedor = new Proveedor();
            $proveedor->nombre_empresa = $request->nombre_empresa;
            $proveedor->contacto_nombre = $request->contacto_nombre;
            $proveedor->contacto_telefono = $request->contacto_telefono;
            $proveedor->contacto_email = $request->contacto_email;
            $proveedor->direccion = $request->direccion;
            $proveedor->save();

            // 🚨 REGISTRO DE HISTORIAL
            HistorialHelper::registrar(
                "Creación de Proveedor",
                "Se registró el proveedor: **{$proveedor->nombre_empresa}** (ID: {$proveedor->id}). Contacto: {$proveedor->contacto_nombre}."
            );

            return redirect()->route('proveedores.index')
                ->with('mensaje', 'Proveedor creado exitosamente.')
                ->with('icono', 'success');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al crear el proveedor: ' . $e->getMessage())
                ->with('icono', 'error')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        // Redirige a la vista de detalle
        return view('admin.proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        // Redirige a la vista de edición
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Usando la sintaxis validate() de la Request (como en otros controladores)
        $request->validate([
            // Excluir el registro actual del check de unicidad
            'nombre_empresa' => 'required|string|max:255|unique:proveedores,nombre_empresa,' . $id, 
            'contacto_nombre' => 'required|string|max:255',
            'contacto_telefono' => 'required|string|max:20',
            'contacto_email' => 'required|email|max:255|unique:proveedores,contacto_email,' . $id,
            'direccion' => 'required|string|max:500',
        ]);

        try {
            $proveedor = Proveedor::findOrFail($id);
            $old_nombre = $proveedor->nombre_empresa;

            $proveedor->nombre_empresa = $request->nombre_empresa;
            $proveedor->contacto_nombre = $request->contacto_nombre;
            $proveedor->contacto_telefono = $request->contacto_telefono;
            $proveedor->contacto_email = $request->contacto_email;
            $proveedor->direccion = $request->direccion;
            $proveedor->save();

            // 🚨 REGISTRO DE HISTORIAL
            HistorialHelper::registrar(
                "Edición de Proveedor",
                "Se actualizó el proveedor ID: {$id}. Nombre anterior: {$old_nombre}, Nuevo nombre: **{$proveedor->nombre_empresa}**."
            );

            return redirect()->route('proveedores.index')
                ->with('mensaje', 'Proveedor actualizado exitosamente.')
                ->with('icono', 'success');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al actualizar el proveedor: ' . $e->getMessage())
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
            $proveedor = Proveedor::findOrFail($id);
            $nombre_proveedor = $proveedor->nombre_empresa; // Capturamos el nombre antes de borrar
            
            $proveedor->delete();

            // 🚨 REGISTRO DE HISTORIAL
            HistorialHelper::registrar(
                "Eliminación de Proveedor",
                "Se eliminó permanentemente el proveedor: **{$nombre_proveedor}** (ID: {$id})."
            );

            return redirect()->route('proveedores.index')
                ->with('mensaje', 'Proveedor eliminado exitosamente.')
                ->with('icono', 'success');
        
        } catch (Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al eliminar el proveedor: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}