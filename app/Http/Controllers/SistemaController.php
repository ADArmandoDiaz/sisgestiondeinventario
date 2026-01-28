<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SistemaController extends Controller
{
            public function __construct()
{
    // Middleware general para que solo usuarios autenticados puedan acceder
    $this->middleware('auth');

    // Permisos por acción
    $this->middleware('can:ver')->only(['index']);
    $this->middleware('can:respaldar datos')->only(['respaldar']);
}
   public function index()
{
    $db_driver = DB::connection()->getDriverName();
    $laravel_version = app()->version();
    $php_version = phpversion();

    // Total de tablas
    $tablas = DB::select('SHOW TABLES');
    $total_tablas = count($tablas);

    // Total de registros en toda la BD
    $total_registros = 0;

    foreach ($tablas as $tabla) {
        // Nombre real de la tabla
        $nombre_tabla = array_values((array)$tabla)[0];

        // Contar registros
        $total_registros += DB::table($nombre_tabla)->count();
    }

    return view('admin.sistema.index', compact(
        'db_driver',
        'laravel_version',
        'php_version',
        'total_tablas',
        'total_registros'
    ));
}

  // app/Http/Controllers/TuControlador.php



public function respaldar()
{
    try {
        // 1. Ejecutar el comando de respaldo de Spatie
        // Esto genera el backup en el disco 'local' por defecto, dentro de una subcarpeta
        Artisan::call('backup:run', ['--only-db' => true]);

        // --- Lógica para encontrar y descargar el archivo ---

        // Configuración del disco y el nombre de la carpeta (revisa tu config/backup.php)
        $diskName = config('backup.backup.destination.disks')[0]; // Debe ser 'local'
        $disk = Storage::disk($diskName);
        
        // 🚨 IMPORTANTE: Reemplaza 'sisgestiondeinventario' por el nombre de la carpeta que Spatie crea
        // El nombre de la carpeta suele ser el nombre de tu aplicación del archivo .env
        $folderName = env('APP_NAME', 'laravel'); 

        // Obtener la lista de archivos en la carpeta de backups
        $files = $disk->files($folderName); 
        
        // Encontrar el archivo más reciente
        $latestFile = collect($files)
            ->sortByDesc(fn($file) => $disk->lastModified($file))
            ->first();

        if (!$latestFile) {
             throw new \Exception('No se encontró el archivo de respaldo generado.');
        }

        // 2. Descargar el archivo
        // Usamos el Facade Storage::disk()->download()
        return $disk->download($latestFile); 
        // Alternativamente, si el IDE insiste en el error:
        // return Storage::disk($diskName)->download($latestFile);

    } catch (\Exception $e) {
        // En caso de error, puedes revisar los logs de Laravel (storage/logs/laravel.log)
        return back()
            ->with('mensaje', 'Error al generar respaldo: ' . $e->getMessage())
            ->with('icono', 'danger');
    }
}

}
