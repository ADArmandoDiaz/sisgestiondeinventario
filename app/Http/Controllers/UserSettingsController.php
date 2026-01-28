<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use App\Helpers\HistorialHelper;


class UserSettingsController extends Controller
{
        public function __construct()
{
    // Middleware general para que solo usuarios autenticados puedan acceder
    $this->middleware('auth');

    // Permisos por acción
    $this->middleware('can:ver')->only(['index', 'editProfile', 'history']);
    $this->middleware('can:eliminar usuarios')->only(['destroy']);
    $this->middleware('can:permisos usuarios')->only(['updatePermissions']);
}
    // Mostrar formulario de perfil
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('admin.users.index', compact('user'));
    }

    // Actualizar información del usuario
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));
        HistorialHelper::registrar(
        "Actualización de Perfil Propio",
        "El usuario **{$user->name}** (ID: {$user->id}) actualizó sus datos básicos."
    );

        return redirect()->back()->with('success', 'Datos actualizados correctamente');
    }

    // Actualizar contraseña
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        // REGISTRO DE HISTORIAL
    HistorialHelper::registrar(
        "Cambio de Contraseña Propia",
        "El usuario **{$user->name}** (ID: {$user->id}) ha cambiado su contraseña."
    );
    // FIN REGISTRO

        return redirect()->back()->with('success', 'Contraseña actualizada correctamente');
    }
 public function editProfile()
{
    $users = User::all();
    $roles = \Spatie\Permission\Models\Role::all();
    $permissions = \Spatie\Permission\Models\Permission::all();

    return view('admin.users.profile', compact('users','roles','permissions'));
}

public function updatePermissions(Request $request, $id)
{
    $user = User::findOrFail($id);

    // 1. Cambiar rol sin tocar permisos
    if ($request->role) {
        $user->syncRoles([$request->role]); // Cambia rol
    } else {
        $user->syncRoles([]); // Sin rol
    }

    // 2. Actualizar permisos individuales SIN mezclar permisos del rol
    $permissions = $request->permissions ?? [];
    $user->syncPermissions($permissions);
    // REGISTRO DE HISTORIAL
    $rol_asignado = $request->role ? "Rol asignado: **{$request->role}**." : "Rol removido.";
    $permisos_cambiados = count($request->permissions ?? []) > 0 ? "Permisos sync: " . implode(', ', $request->permissions) : "Sin permisos individuales.";

    HistorialHelper::registrar(
        "Modificación de Permisos de Usuario",
        "Se actualizaron los permisos del usuario **{$user->name}** (ID: {$user->id}). {$rol_asignado} | {$permisos_cambiados}"
    );
    // FIN REGISTRO

    return back()->with('success', 'Permisos actualizados correctamente.');
}
public function destroy($id)
{
    $user = User::findOrFail($id);
    $currentUser = Auth::user();

    if ($currentUser && $currentUser->id === $user->id) {
        return back()->with('mensaje', 'No puedes eliminar tu propio usuario.')
                     ->with('icono', 'error');
    }
    $user_name = $user->name; // Guardamos el nombre antes de borrar
    $user_id = $user->id; // Guardamos el ID antes de borrar
    $user->delete();
    // REGISTRO DE HISTORIAL
    HistorialHelper::registrar(
        "Eliminación de Usuario",
        "Se eliminó permanentemente al usuario **{$user_name}** (ID: {$user_id})."
    );
    // FIN REGISTRO

    return back()->with('mensaje', 'Usuario eliminado correctamente.')
                 ->with('icono', 'success');
}
public function history()
{
    $logs = \App\Models\Historial::with('user')
        ->latest()
        ->paginate(20);

    return view('admin.users.history', compact('logs'));
}





}
