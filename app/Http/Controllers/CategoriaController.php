<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Helpers\HistorialHelper; // <-- 1. Importación del Helper

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
{
    // Middleware general para que solo usuarios autenticados puedan acceder
    $this->middleware('auth');

    // Permisos por acción
    $this->middleware('can:ver')->only(['index', 'show', 'create', 'edit']);
    $this->middleware('can:crear')->only([ 'store']);
    $this->middleware('can:editar')->only([ 'update']);
    $this->middleware('can:eliminar')->only(['destroy']);
}
    public function index()
    {
        $categorias = Categoria::all();
        // echo $categorias;
        // return response()->json($categorias);
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
    'nombre' => [
        'required',
        'string',
        'max:255',
        'regex:/^[\pL\s]+$/u',
        'unique:categorias,nombre'
    ],
], [
    'nombre.required' => 'El nombre de la categoría es obligatorio.',
    'nombre.string'   => 'El nombre debe ser una cadena de texto válida.',
    'nombre.max'      => 'El nombre no debe superar los 255 caracteres.',
    'nombre.regex'    => 'El nombre solo puede contener letras y espacios.',
    'nombre.unique'   => 'Ya existe una categoría con este nombre.',
]);

        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->description = $request->description;
        $categoria->save();
         // 🚨 REGISTRO DE HISTORIAL
        HistorialHelper::registrar(
            "Creación de Categoría",
            "Se creó la categoría: **{$categoria->nombre}** (ID: {$categoria->id})."
        );
        // FIN REGISTRO DE HISTORIAL

        return redirect()->route('categorias.index')
        ->with('mensaje', 'Categoría creada exitosamente.')
        ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        // return response()->json($categoria);
        return view('admin.categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // return response()->json($request->all());

       $request->validate([
    'nombre' => [
        'required',
        'string',
        'max:255',
        'regex:/^[\pL\s]+$/u',
        'unique:categorias,nombre,' . $id  // <--- CORRECTO
    ],
], [
    'nombre.required' => 'El nombre de la categoría es obligatorio.',
    'nombre.string'   => 'El nombre debe ser una cadena de texto válida.',
    'nombre.max'      => 'El nombre no debe superar los 255 caracteres.',
    'nombre.regex'    => 'El nombre solo puede contener letras y espacios.',
    'nombre.unique'   => 'Ya existe una categoría con este nombre.',
]);


        $categoria = Categoria::findOrFail($id);
        $old_nombre = $categoria->nombre; // Capturamos el nombre anterior
        $categoria->nombre = $request->nombre;
        $categoria->description = $request->description;
        $categoria->save();
         // 🚨 REGISTRO DE HISTORIAL
        HistorialHelper::registrar(
            "Edición de Categoría",
            "Se modificó la categoría ID: {$categoria->id}. Nombre anterior: {$old_nombre}, Nuevo nombre: **{$categoria->nombre}**."
        );
        // FIN REGISTRO DE HISTORIAL

        return redirect()->route('categorias.index')
        ->with('mensaje', 'Categoría actualizada exitosamente.')
        ->with('icono', 'success');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
         $nombre_categoria = $categoria->nombre; // Capturamos el nombre antes de borrar
        $categoria->delete();
          // 🚨 REGISTRO DE HISTORIAL
        HistorialHelper::registrar(
            "Eliminación de Categoría",
            "Se eliminó permanentemente la categoría: **{$nombre_categoria}** (ID: {$id})."
        );
        // FIN REGISTRO DE HISTORIAL

        return redirect()->route('categorias.index')
        ->with('mensaje', 'Categoría eliminada exitosamente.')
        ->with('icono', 'success');
    }
}
