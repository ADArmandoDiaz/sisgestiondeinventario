<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSettingsController;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\AdminController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', action: [App\Http\Controllers\AdminController::class,'index'])->name('admin.index')->middleware('auth');




// Rutas para categorias

Route::get('/admin/categorias', [App\Http\Controllers\CategoriaController::class,'index'])->name('categorias.index')->middleware('auth');
Route::get('/admin/categorias/create', [App\Http\Controllers\CategoriaController::class,'create'])->name('categorias.create')->middleware('auth');
Route::post('/admin/categorias/create', [App\Http\Controllers\CategoriaController::class,'store'])->name('categorias.store')->middleware('auth');
Route::get('/admin/categorias/{id}', [App\Http\Controllers\CategoriaController::class,'show'])->name('categorias.show')->middleware('auth');
Route::get('/admin/categorias/{id}/edit', [App\Http\Controllers\CategoriaController::class,'edit'])->name('categorias.edit')->middleware('auth');
Route::put('/admin/categorias/{id}', [App\Http\Controllers\CategoriaController::class,'update'])->name('categorias.update')->middleware('auth');
Route::delete('/admin/categorias/{id}', [App\Http\Controllers\CategoriaController::class,'destroy'])->name('categorias.destroy')->middleware('auth');


// Rutas para categorias

// Route::get('/admin/sucursales', [App\Http\Controllers\SucursalController::class,'index'])->name('sucursales.index')->middleware('auth');
// Route::get('/admin/sucursales/create', [App\Http\Controllers\SucursalController::class,'create'])->name('sucursales.create')->middleware('auth');
// Route::post('/admin/sucursales/create', [App\Http\Controllers\SucursalController::class,'store'])->name('sucursales.store')->middleware('auth');
// Route::get('/admin/sucursales/{id}', [App\Http\Controllers\SucursalController::class,'show'])->name('sucursales.show')->middleware('auth');
// Route::get('/admin/sucursales/{id}/edit', [App\Http\Controllers\SucursalController::class,'edit'])->name('sucursales.edit')->middleware('auth');
// Route::put('/admin/sucursales/{id}', [App\Http\Controllers\SucursalController::class,'update'])->name('sucursales.update')->middleware('auth');
// Route::delete('/admin/sucursales/{id}', [App\Http\Controllers\SucursalController::class,'destroy'])->name('sucursales.destroy')->middleware('auth');
// Rutas para productos

Route::get('/admin/productos', [App\Http\Controllers\ProductoController::class,'index'])->name('productos.index')->middleware('auth');
Route::get('/admin/productos/create', [App\Http\Controllers\ProductoController::class,'create'])->name('productos.create')->middleware('auth');
Route::post('/admin/productos/create', [App\Http\Controllers\ProductoController::class,'store'])->name('productos.store')->middleware('auth');
Route::get('/admin/productos/{id}', [App\Http\Controllers\ProductoController::class,'show'])->name('productos.show')->middleware('auth');
Route::get('/admin/productos/{id}/edit', [App\Http\Controllers\ProductoController::class,'edit'])->name('productos.edit')->middleware('auth');
Route::put('/admin/productos/{id}', [App\Http\Controllers\ProductoController::class,'update'])->name('productos.update')->middleware('auth');
Route::delete('/admin/productos/{id}', [App\Http\Controllers\ProductoController::class,'destroy'])->name('productos.destroy')->middleware('auth');
// Rutas para proveedores

Route::get('/admin/proveedores', [App\Http\Controllers\ProveedorController::class,'index'])->name('proveedores.index')->middleware('auth');
Route::post('/admin/proveedores/create', [App\Http\Controllers\ProveedorController::class,'store'])->name('proveedores.store')->middleware('auth');
Route::put('/admin/proveedores/{id}', [App\Http\Controllers\ProveedorController::class,'update'])->name('proveedores.update')->middleware('auth');
Route::delete('/admin/proveedores/{id}', [App\Http\Controllers\ProveedorController::class,'destroy'])->name('proveedores.destroy')->middleware('auth');
// Rutas para compras

// Route::get('/admin/compras', [App\Http\Controllers\CompraController::class,'index'])->name('compras.index')->middleware('auth');
// Route::get('/admin/compras/create', [App\Http\Controllers\CompraController::class,'create'])->name('compras.create')->middleware('auth');
// Route::post('/admin/compras/create', [App\Http\Controllers\CompraController::class,'store'])->name('compras.store')->middleware('auth');
// Route::get('/admin/compras/{id}/edit', [App\Http\Controllers\CompraController::class,'edit'])->name('compras.edit')->middleware('auth');
// Route::put('/admin/compras/{id}', [App\Http\Controllers\CompraController::class,'update'])->name('compras.update')->middleware('auth');
// Route::delete('/admin/compras/{id}', [App\Http\Controllers\CompraController::class,'destroy'])->name('compras.destroy')->middleware('auth');

// Rutas para gestionar usuarios
Route::middleware(['auth'])->group(function () {
    
    Route::get('/admin/users', [UserSettingsController::class, 'edit'])->name('admin.users');
    Route::post('/admin/users', [UserSettingsController::class, 'update'])->name('admin.users.update');
    Route::post('/admin/users/password', [UserSettingsController::class, 'updatePassword'])->name('admin.users.password');
    Route::get('/admin/users/profile', [UserSettingsController::class,'editProfile'])->name('admin.users.profile');
    Route::delete('/admin/users/{id}', [UserSettingsController::class,'destroy'])->name('admin.users.destroy');

    // ⭐ RUTA PARA ACTUALIZAR PERMISOS DE UN USUARIO (modal)
    Route::post('/admin/users/{id}/permissions', 
        [UserSettingsController::class, 'updatePermissions']
    )->name('admin.users.updatePermissions');
    // Historial de acciones de usuarios
Route::get('/admin/users/history', [UserSettingsController::class, 'history'])
    ->name('admin.users.history');


});

use App\Http\Controllers\MovimientoController;
// use Illuminate\Support\Facades\Route;

// Agrupamos todas las rutas de movimientos bajo el prefijo 'admin' y el middleware 'auth'.
Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    // 1. INDEX (Listar todos)
    Route::get('/movimientos', [MovimientoController::class, 'index'])
        ->name('admin.movimientos.index');

    // 2. CREATE (Mostrar formulario de creación)
    Route::get('/movimientos/create', [MovimientoController::class, 'create'])
        ->name('admin.movimientos.create');

    // 3. STORE (Guardar nuevo movimiento)
    Route::post('/movimientos', [MovimientoController::class, 'store'])
        ->name('admin.movimientos.create');

    // 4. SHOW (Mostrar un movimiento específico)
    // Se necesita el parámetro {movimiento} para identificar el registro
    Route::get('/movimientos/{movimiento}', [MovimientoController::class, 'show'])
        ->name('admin.movimientos.show');

    // 5. EDIT (Mostrar formulario de edición)
    Route::get('/movimientos/{movimiento}/edit', [MovimientoController::class, 'edit'])
        ->name('admin.movimientos.edit');

    // 6. UPDATE (Actualizar el movimiento)
    // Usa los métodos PUT/PATCH para actualizar recursos
    Route::put('/movimientos/{movimiento}', [MovimientoController::class, 'update'])
        ->name('admin.movimientos.update');

    // 7. DESTROY (Eliminar el movimiento)
    Route::delete('/movimientos/{movimiento}', [MovimientoController::class, 'destroy'])
        ->name('admin.movimientos.destroy');

});
use App\Http\Controllers\LoteController;

Route::get('/admin/lotes', [LoteController::class,'index'])->name('lotes.index')->middleware('auth');
Route::get('/admin/lotes/create', [LoteController::class,'create'])->name('lotes.create')->middleware('auth');
Route::post('/admin/lotes/create', [LoteController::class,'store'])->name('lotes.store')->middleware('auth');
Route::get('/admin/lotes/{id}', [LoteController::class,'show'])->name('lotes.show')->middleware('auth');
Route::get('/admin/lotes/{id}/edit', [LoteController::class,'edit'])->name('lotes.edit')->middleware('auth');
Route::put('/admin/lotes/{id}', [LoteController::class,'update'])->name('lotes.update')->middleware('auth');
Route::delete('/admin/lotes/{id}', [LoteController::class,'destroy'])->name('lotes.destroy')->middleware('auth');


// Vista del sistema
Route::get('/admin/sistema', [App\Http\Controllers\SistemaController::class, 'index'])
    ->name('sistema.index')
    ->middleware('auth');

// Respaldo de BD
Route::post('/admin/sistema/respaldo', [App\Http\Controllers\SistemaController::class, 'respaldar'])
    ->name('sistema.respaldar')
    ->middleware('auth');
