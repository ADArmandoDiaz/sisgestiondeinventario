<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Lote;
use App\Models\User;
use App\Models\Movimiento;

class AdminController extends Controller
{
    public function index()
    {
        $total_categorias = Categoria::count();
        $total_usuarios = User::count();
        $total_lotes = Lote::count();

        $total_productos = Producto::count();
        $problemas_stock = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
    ->orWhereColumn('stock_actual', '>=', 'stock_maximo')
    ->count();

        $total_proveedores = Proveedor::count();
        $total_movimientos = Movimiento::count();

        // 🔥 Nueva línea: Total de lotes vencidos
        $total_lotes_vencidos = Lote::where('fecha_vencimiento', '<', now())->count();

        return view('admin.index', compact(
            'total_movimientos',
            'total_categorias',
            'total_productos',
            'total_proveedores',
            'total_lotes',
            'total_lotes_vencidos', // <-- enviado a la vista
            'total_usuarios',
            'problemas_stock'
        ));
    }
}
